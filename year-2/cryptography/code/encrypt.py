from math import log

def egcd(a, b):
    x,y, u,v = 0,1, 1,0
    while a != 0:
        q, r = b//a, b%a
        m, n = x-u*q, y-v*q
        b,a, x,y, u,v = a,r, u,v, m,n
    gcd = b
    return gcd, x, y

def affine_encrypt(text, key):
    '''
    C = (a*P + b) % 26
    '''
    return ''.join([ chr((( key[0]*(ord(t) - ord('A')) + key[1] ) % 26)
                  + ord('A')) for t in text.upper().replace(' ', '') ])

def main():
    plaintext = input("please enter the text you'd like to encrypt: ")
    print(" ")
    mod = 26
    #mod = int(input("please enter the modulus you'd like to use ")) # asks user for what modulous theyd like to use
    #print(" ")
    key1imp = int(input("please enter key 1 (A): "))
    k1 = False
    k2 = False
    
    while k1 == False:
        gcd, x, y = egcd(key1imp, 26)
        if gcd == 1:
           key1 = key1imp
           k1 = True 
           print("key Accepted")
           print(" ")
           break

        if k1 == False:
            #print(" ")
            print("key Declined")
            print("key must be co-prime with the modulus (26)\n")
            key1imp = int(input("please enter key 1 (A): "))
                    
    key2imp = int(input("please enter key 2 (B): "))
    key2 = key2imp
    print("key accepted")

    #encryption and printing                    
    key = [key1,key2]
    ciphertext = affine_encrypt(plaintext,key)
    print(" ")
    print("encrypting text.....")
    print("plaintext: ",plaintext)
    print("modulus: ",mod)
    print("key: ",key)
    print("cithertext: ",ciphertext)



if __name__ == '__main__':
   print("this is the encryption tool that will encrypt your plaintext with the given key")
   print("please note that this tool is set to use modular 26 (a-z)\n")
   main()
