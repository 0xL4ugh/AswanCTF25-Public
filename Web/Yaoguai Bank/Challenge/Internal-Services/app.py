from flask import Flask, request, jsonify
from services.transfer_service import transfer_funds

app = Flask(__name__)

@app.route('/transfer', methods=['GET'])
def transfer():
    from_acc = request.args.get('from_account')
    to_acc = request.args.get('to_account')
    amount = request.args.get('amount')
    reference = request.args.get('reference_number')
    print(from_acc)
    print(to_acc)
    print(amount)
    print(reference)

    if not all([from_acc, to_acc, amount, reference]):
        return jsonify({"status": "error", "message": "Missing required fields"}), 400

    try:
        amount = int(amount)
        result = transfer_funds(from_acc, to_acc, amount)
        return jsonify(result)
    except ValueError:
        return jsonify({"status": "error", "message": "Amount must be a number"}), 400
    except Exception as e:
        return jsonify({"status": "error", "message": str(e)}), 500

if __name__ == '__main__':
    app.run(debug=False)
