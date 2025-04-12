from Crypto.Util.number import getPrime,long_to_bytes

FLAG=b'YAO{i_didnot_guard_it_right}'
m=b'give_me_the_flag'


p=getPrime(512)
q=getPrime(512)
n=p*q
phi=(p-1)*(q-1)
e=0x10001
d=pow(e,-1,phi)
def sign(msg):
	signed=pow(msg,d,n)
	return signed
def verify(s,m):
	msg=pow(s,e,n)
	return long_to_bytes(msg)==m
banner='''1.sign
2.verify
3.exit'''


while True:
	print(banner)
	option=int(input('> '))
	if option==1:
		msg=int(input('enter your msg as hex > '),16)
		if msg==m:
			print('that will not work here ')
			exit()
		s=sign(msg)
		print(f'here is your signed msg : {hex(s)}')

	elif option==2:
		s=int(input('enter your signed msg as hex > '),16)
		if verify(s,m):
			print(f'you got me this time here is the flag : {FLAG}')
			exit()
		else:
			print('wrong msg try harder ')
	else:
		exit()
		











