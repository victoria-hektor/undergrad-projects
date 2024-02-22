from math import log

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


def infer_spaces(s): # function to split a string into words
    words = open("dictionary.txt", encoding="latin-1").read().split() 
    wordcost = dict((k, log((i+1)*log(len(words)))) for i,k in enumerate(words))
    maxword = max(len(x) for x in words)
    
    def best_match(i):
        candidates = enumerate(reversed(cost[max(0, i-maxword):i]))
        return min((c + wordcost.get(s[i-k-1:i], 9e999), k+1) for k,c in candidates)

    # Build the cost array.
    cost = [0]
    for i in range(1,len(s)+1):
        c,k = best_match(i)
        cost.append(c)

    # Backtrack to recover the minimal-cost string.
    out = []
    i = len(s)
    while i>0:
        c,k = best_match(i)
        assert c == cost[i]
        out.append(s[i-k:i])
        i -= k

    return " ".join(reversed(out))
   
def main():

    ciphertext = input("please enter the text you'd like to decrypt: ")
    print(" ")
    mod = 26
    #mod = int(input("please enter the modulus you'd like to use ")) # asks user for what modulous theyd like to use
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
    plaintext = affine_decrypt(ciphertext,key,mod)# decrpty code
    ptlower = plaintext.lower()
    sentance = (infer_spaces(ptlower))
    print(" ")
    print("Decrypting text.....")
    print("cithertext: ",ciphertext)
    print("modulus: ",mod)
    print("key: ",key)
    print("plaintext: ",plaintext)
    print("Sentance: ",sentance)
    print(input("")



if __name__ == '__main__':
   print("this is the decryption tool that will decrypt your ciphertext with the given key")
   print("please note that this tool is set to use modular 26 (a-z)\n")
   main()
