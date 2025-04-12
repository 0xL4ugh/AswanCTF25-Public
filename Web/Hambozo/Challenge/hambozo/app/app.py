from flask import Flask, request, render_template_string

app = Flask(__name__)

index_template = """
<!DOCTYPE html>
<html>
<head>
    <title>Index</title>
    </head>
        <body>
<form action="/say_Hello" method="get">
    <label for="name">Enter your name:</label>
    <input type="text" id="name" name="name" required>
    <button type="submit">Say Hello</button>
</form>
</body>
</html>
"""
@app.route('/')
def index():
    return render_template_string(index_template)

@app.route('/say_Hello')
def say_hello():
    return f'Hello, {request.args["name"]}'

@app.get('/read')
def vuln():
    if request.args.get('file', None):
        try:
            file = request.args.get('file', None)
            with open(file, 'r') as f:
                return f.read()
        except Exception as e:
            return str(e)
    else:
        return 'No file provided!'

if __name__ == '__main__':
    app.run(host='0.0.0.0',debug=True)