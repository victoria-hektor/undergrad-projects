
def compute_hcf(x, y):
   while(y):
       x, y = y, x % y
   return x


def egcd(a, b):
    x,y, u,v = 0,1, 1,0
    while a != 0:
        q, r = b//a, b%a
        m, n = x-u*q, y-v*q
        b,a, x,y, u,v = a,r, u,v, m,n
    gcd = b
    return gcd, x, y
 
def modinv(a, m):
    gcd, x, y = egcd(a, m)
    if gcd != 1:
        return None  # modular inverse does not exist
    else:
        return x % m

def affine_decrypt(cipher, key, mod):
    '''
    P = (a^-1 * (C - b)) % 26
    '''
    return ''.join([ chr((( modinv(key[0], 26)*(ord(c) - ord('A') - key[1]))
                    % mod) + ord('A')) for c in cipher ])

def main():

    ciphertext = input("please enter the text you'd like to decrypt: ")
    print(" ")
    mod = int(input("please enter the modulus you'd like to use ")) # asks user for what modulous theyd like to use
    print(" ")
    key1imp = int(input("please enter key 1 (A): "))
    k1 = False
    k2 = False
    
    while k1 == False:
        gcd, x, y = egcd(key1imp, mod)
        
        if gcd == 1:
           key1 = key1imp
           k1 = True 
           print("key Accepted")
           print(" ")
           break

        if k1 == False:
            print(" ")
            print("key Declined")       
            key1imp = int(input("please enter key 1 (A): "))
                    
    key2imp = int(input("please enter key 2 (B): "))
    key2 = key2imp
    print("key accepted")

    # decryption and printing
    key = [key1,key2]
    plaintext = affine_decrypt(ciphertext,key,mod)
    print(" ")
    print("Decrypting text.....")
    print("cithertext: ",ciphertext)
    print("modulus: ",mod)
    print("key: ",key)
    print("plaintext: ",plaintext)



if __name__ == '__main__': 
  main()
