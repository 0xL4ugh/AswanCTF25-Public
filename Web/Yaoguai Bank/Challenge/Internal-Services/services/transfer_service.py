import pymysql
from datetime import datetime
import os

# TO-Do: Implement ReferenceNumber Logic
def transfer_funds(from_account, to_account, amount):
    connection = None  # Initialize connection to None to avoid "before assignment" error
    try:
        connection = pymysql.connect(
            host=os.getenv('MYSQL_HOST', 'mysql'), 
            user=os.getenv('MYSQL_USER', 'root'),
            password=os.getenv('MYSQL_ROOT_PASSWORD', 'root_password'),
            database=os.getenv('MYSQL_DATABASE', 'yaobank'),
            port=int(os.getenv('MYSQL_PORT', 3306))  # Ensure port is an integer
        )

        if not connection.open:
            return {"status": "error", "message": "DB connection failed"}

        cursor = connection.cursor()

        connection.begin()  # Start a transaction

        cursor.execute("SELECT Balance FROM bankaccounts WHERE AccountNumber = %s", (from_account,))
        from_acc = cursor.fetchone()

        cursor.execute("SELECT Balance FROM bankaccounts WHERE AccountNumber = %s", (to_account,))
        to_acc = cursor.fetchone()

        if not from_acc or not to_acc:
            return {"status": "error", "message": "One or both accounts not found"}

        cursor.execute("UPDATE bankaccounts SET Balance = Balance - %s WHERE AccountNumber = %s", (amount, from_account))
        cursor.execute("UPDATE bankaccounts SET Balance = Balance + %s WHERE AccountNumber = %s", (amount, to_account))

        cursor.execute(
            "INSERT INTO transactions (FromAcc, ToAcc, Amount, Date) VALUES (%s, %s, %s, %s)",
            (from_account, to_account, amount, datetime.now())
        )
        connection.commit()  # Commit the transaction

        return {
            "status": "success",
            "message": f"Transferred {amount} from {from_account} to {to_account}"
        }

    except Exception as e:
        if connection and connection.open:  # Check if connection is initialized and open
            connection.rollback()  # Rollback if any error occurs
        return {"status": "error", "message": str(e)}

    finally:
        if connection and connection.open:  # Ensure connection is open before closing
            cursor.close()
            connection.close()
