# ==============================
# IMPORTS
# ==============================

from flask import Flask, render_template, request, redirect, url_for, session, flash
from functools import wraps
import mysql.connector
import bcrypt

# ==============================
# APP CONFIGURATIE
# ==============================

app = Flask(__name__)
app.secret_key = "super_secret_key_change_me"

# ==============================
# DATABASE CONNECTIE FUNCTIE
# ==============================

def get_db_connection():
    try:
        connection = mysql.connector.connect(
            host='localhost',
            port='3306',
            user='root',
            password='',
            database='Login_crud'
        )
        return connection
    except mysql.connector.Error as err:
        print("Database connectie mislukt:", err)
        return None

# ==============================
# AUTHENTICATIE HELPERS
# ==============================

def hash_password(plain_password):
    password_bytes = plain_password.encode('utf-8')
    hashed = bcrypt.hashpw(password_bytes, bcrypt.gensalt())
    return hashed.decode('utf-8')

def check_password(plain_password, hashed_password):
    return bcrypt.checkpw(
        plain_password.encode('utf-8'),
        hashed_password.encode('utf-8')
    )

def login_required(f):
    @wraps(f)
    def decorated_function(*args, **kwargs):
        if "user_id" not in session:
            flash("Je moet ingelogd zijn om deze pagina te bekijken.", "warning")
            return redirect(url_for("login"))
        return f(*args, **kwargs)
    return decorated_function

def admin_required(f):
    @wraps(f)
    def decorated_function(*args, **kwargs):
        if "user_id" not in session:
            flash("Je moet ingelogd zijn.", "warning")
            return redirect(url_for("login"))

        if session.get("role") != "admin":
            flash("Je hebt geen rechten om deze pagina te bekijken.", "danger")
            return redirect(url_for("dashboard"))

        return f(*args, **kwargs)
    return decorated_function

# ==============================
# ROUTE: REGISTER
# ==============================

@app.route("/register", methods=["GET", "POST"])
def register():
    if request.method == "POST":
        username = request.form.get("username")
        password = request.form.get("password")
        email = request.form.get("email")

        if not username or not password or not email:
            flash("Vul alle velden in.", "warning")
            return redirect(url_for("register"))

        conn = get_db_connection()
        if conn is None:
            flash("Database connectie mislukt.", "danger")
            return redirect(url_for("register"))

        cursor = conn.cursor(dictionary=True)

        # Check of gebruiker al bestaat
        cursor.execute(
            "SELECT * FROM users WHERE username = %s OR email = %s",
            (username, email)
        )
        result = cursor.fetchone()

        if result:
            flash("Gebruikersnaam of e-mail bestaat al.", "danger")
            cursor.close()
            conn.close()
            return redirect(url_for("register"))

        hashed_password = hash_password(password)

        cursor.execute(
            "INSERT INTO users (username, password, email, role, active) VALUES (%s, %s, %s, %s, %s)",
            (username, hashed_password, email, "user", 1)
        )
        conn.commit()
        cursor.close()
        conn.close()

        flash("Account succesvol aangemaakt. Je kunt nu inloggen.", "success")
        return redirect(url_for("login"))

    return render_template("register.html")

# ==============================
# ROUTE: LOGIN
# ==============================

@app.route("/login", methods=["GET", "POST"])
def login():
    if request.method == "POST":
        login_value = request.form.get("login")
        password = request.form.get("password")

        if not login_value or not password:
            flash("Vul alle velden in.", "warning")
            return redirect(url_for("login"))

        conn = get_db_connection()
        cursor = conn.cursor(dictionary=True)

        cursor.execute(
            """
            SELECT * FROM users
            WHERE (username = %s OR email = %s)
            AND active = 1
            """,
            (login_value, login_value)
        )
        user = cursor.fetchone()

        cursor.close()
        conn.close()

        if user is None:
            flash("Ongeldige login gegevens.", "danger")
            return redirect(url_for("login"))

        if not check_password(password, user["password"]):
            flash("Ongeldige login gegevens.", "danger")
            return redirect(url_for("login"))

        # ✅ Login succesvol
        session["user_id"] = user["Id"]
        session["username"] = user["username"]
        session["role"] = user["role"]

        flash("Succesvol ingelogd!", "success")

        if user["role"] == "admin":
            return redirect(url_for("admin_dashboard"))
        return redirect(url_for("dashboard"))

    return render_template("login.html")


# ==============================
# ROUTE: LOGOUT
# ==============================

@app.route("/logout")
@login_required
def logout():
    session.clear()
    flash("Je bent uitgelogd.", "info")
    return redirect(url_for("login"))

# ==============================
# ROUTE: USER DASHBOARD
# ==============================

@app.route("/dashboard")
@login_required
def dashboard():
    return render_template(
        "dashboard.html",
        username=session.get("username"),
        role=session.get("role")
    )

# ==============================
# ROUTE: ADMIN DASHBOARD
# ==============================

@app.route("/admin")
@admin_required
def admin_dashboard():
    return render_template(
        "admin_dashboard.html",
        username=session.get("username"),
        role=session.get("role")
    )

# ==============================
# ROUTE: ADMIN - USERS OVERZICHT
# ==============================

@app.route("/admin/users", methods=["GET", "POST"])
@admin_required
def manage_users():
    conn = get_db_connection()
    cursor = conn.cursor(dictionary=True)

    if request.method == "POST":
        action = request.form.get("action")

        # CREATE USER
        if action == "create":
            username = request.form.get("username")
            email = request.form.get("email")
            password = request.form.get("password")
            role = request.form.get("role")

            if not username or not email or not password:
                flash("Alle velden zijn verplicht.", "warning")
            else:
                hashed = hash_password(password)
                cursor.execute(
                    "INSERT INTO users (username, email, password, role, active) VALUES (%s,%s,%s,%s,1)",
                    (username, email, hashed, role)
                )
                conn.commit()
                flash("Gebruiker toegevoegd.", "success")

        # UPDATE USER
        elif action == "update":
            user_id = request.form.get("user_id")
            username = request.form.get("username")
            email = request.form.get("email")
            role = request.form.get("role")

            cursor.execute(
                "UPDATE users SET username=%s, email=%s, role=%s WHERE Id=%s",
                (username, email, role, user_id)
            )
            conn.commit()
            flash("Gebruiker bijgewerkt.", "success")

        # TOGGLE ACTIVE
        elif action == "toggle_active":
            user_id = request.form.get("user_id")
            cursor.execute(
                "UPDATE users SET active = NOT active WHERE Id=%s",
                (user_id,)
            )
            conn.commit()
            flash("Gebruiker status aangepast.", "info")

        # DELETE USER
        elif action == "delete":
            user_id = request.form.get("user_id")

            if int(user_id) == session.get("user_id"):
                flash("Je kunt jezelf niet verwijderen.", "danger")
            else:
                cursor.execute("DELETE FROM users WHERE Id=%s", (user_id,))
                conn.commit()
                flash("Gebruiker verwijderd.", "success")

        return redirect(url_for("manage_users"))

    # GET → users ophalen
    cursor.execute("SELECT Id, username, email, role, active FROM users")
    users = cursor.fetchall()

    cursor.close()
    conn.close()

    return render_template(
        "manage_users.html",
        users=users,
        admin_username=session.get("username")
    )


# ==============================
# RUN APP
# ==============================

if __name__ == "__main__":
    app.run(debug=True)
