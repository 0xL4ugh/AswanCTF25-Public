
from Crypto.Util.number import bytes_to_long as b2l
from secret import a,b,p FLAG

print(p)
E = EllipticCurve(GF(p), [a, b])
G=E.gen(0)[0]
print(G)
z=E.lift_x(b2l(FLAG)+1)
d=randint(0, 2**220)
Q=d*G
print(Q)
flag_enc=d*z
print(flag_enc)















