from Crypto.Cipher import AES
from Crypto.Util.Padding import pad, unpad
from Crypto.Util import Counter


import os

def bxor(a, b):
    return bytes(x ^ y for x, y in zip(a, b))

msg=b'can u retreve this message back from another message if your did here is the flag : YAO{CTR_with_same_iv_is_bad}'
known=b'Crime_time_Right?'

iv = int.from_bytes(os.urandom(16), 'big')
KEY=os.urandom(16)

def encrypt(plaintext):
    
    cipher = AES.new(KEY, AES.MODE_CTR, counter=Counter.new(128, initial_value=iv))
    encrypted = cipher.encrypt(plaintext)
    return encrypted


known=[known[i%len(known)] for i in range(len(msg))]
print(encrypt(encrypt(bxor(msg,known))).hex())
print(encrypt(encrypt(bytes(known))).hex())
		
		





