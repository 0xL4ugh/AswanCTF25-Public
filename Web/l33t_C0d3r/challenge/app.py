from flask import Flask, request, render_template
import subprocess

app = Flask(__name__)

@app.route('/', methods=['GET', 'POST'])
def index():
    result = ""
    if request.method == 'POST':
        number = request.form.get('number', '')

        try:
            val = int(number)
            if val == 1337:
                result = "Nope! You are not l33t enough! Try again."
                return render_template('index.html', result=result)
        except ValueError:
            result = "❌ Invalid number."
            return render_template('index.html', result=result)

        try:
            output = subprocess.check_output(
                ['./server'], input=number.encode(), stderr=subprocess.STDOUT
            )
            result = output.decode()
        except subprocess.CalledProcessError as e:
            result = "Error communicating with the backend."

    return render_template('index.html', result=result)

if __name__ == '__main__':
    app.run('0.0.0.0')
