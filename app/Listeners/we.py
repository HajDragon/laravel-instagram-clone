import bcrypt

hashed_password = b"$2y$12$K1R5/iJypt3WaHiZsIT3MuJMDPAuNmmREKHZ1tUwzZmFAweNNFeKW"
password_attempt = b"jouw_wachtwoord"

if bcrypt.checkpw(password_attempt, hashed_password):
    print("Wachtwoord klopt!")
else:
    print("Wachtwoord is onjuist.")
