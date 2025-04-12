from pwn import *
from gmpy2 import gcd
from Crypto.Util.number import bytes_to_long as b2l
from Crypto.Util.number import long_to_bytes as l2b

r=remote('127.0.0.1',1333, level='debug')

p=[2,3,5,7]
pp=[4,9,25,49]
p1=[]
pp1=[]
for i in range(4):
	r.recvuntil(b'> ')
	r.sendline(b'1')
	r.recvuntil(b'enter your msg as hex > ')
	r.sendline(hex(p[i]).encode())
	r.recvuntil(b'here is your signed msg : ')
	p1.append(int(r.recvline().decode(),16))
	r.recvuntil(b'> ')
	r.sendline(b'1')
	r.recvuntil(b'enter your msg as hex > ')
	r.sendline(hex(pp[i]).encode())
	r.recvuntil(b'here is your signed msg : ')
	pp1.append(int(r.recvline().decode(),16))	
res=[p1[i]**2-pp1[i] for i in range(4)]

n=res[0]
for i in res:
	n=gcd(n,i)
print(n)
m=(b2l(b'give_me_the_flag')*2**0x10001 )%n
r.recvuntil(b'> ')
r.sendline(b'1')
r.recvuntil(b'enter your msg as hex > ')
r.sendline(hex(m).encode())
r.recvuntil(b'here is your signed msg : ')
ct=int(r.recvline().decode(),16)
ct=pow(2,-1,n)*ct %n
r.recvuntil(b'> ')
r.sendline(b'2')
r.recvuntil(b'enter your signed msg as hex > ')
r.sendline(hex(ct).encode())
print(r.recv())







