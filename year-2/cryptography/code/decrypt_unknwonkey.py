from math import log # imports

def egcd(a, b): # extended euclidean function
  x,y, u,v = 0,1, 1,0
  while a != 0: 
    q, r = b//a, b%a  
    m, n = x-u*q, y-v*q 
    b,a, x,y, u,v = a,r, u,v, m,n 
  gcd = b 
  return gcd, x, y

def modinv(a, m): # Finding the inverse of the first key (GCD)
  gcd, x, y = egcd(a, m) 
  if gcd != 1: 
    return None # modular inverse does not exist 
  else: 
    return x % m 

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


def convert(lst): # function convert a sentance into an array
    return ([i for item in lst for i in item.split()])
  
# Driver Code
def main():
          text = input("please enter the cipher text: ")
          key1 = [1, 3, 5, 7, 9, 11, 15, 17, 19, 21, 23, 25] # key value 1 (A) (12 possabilities) has to be co-prime with the mod
          key2 = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25] # key value 2 (26 possabilities)
          i = 0
          j = 0
          key1len=len(key1)
          key2len=len(key2)
          possabilities =[]
          plaintexts = []
          sentance = []
          for i in range(key2len): # for loop of key B
              g = key2[i]
              for j in range(key1len): # for loops of key A
                 f = key1[j]
                 j = j+1
                 key = [f, g] 
                 fstr = int(f)
                 gstr = int(g)
                 decrypt =  (''.join([ chr((( modinv(key[0], 26)*(ord(c) - ord('A') - key[1])) % 26) + ord('A')) for c in text ])) # code to decrypt 

                 long_string = decrypt.lower() # converst the decrypted text to lowercase
                 possiblewords = (infer_spaces(long_string)) # function to find the words in a long string
                 lst =  [possiblewords] # adds that to an array
                 splitlist = convert(lst) # splits the words into an array
                 matches = [] # declares an array
                 
                 for o in splitlist:# for each word 
                   #print (o)
                   words = open("dictionary.txt", encoding="latin-1").read().split() # opens files
                   if o in words: # if word is in the dictionary 
                     matches.append(o) # add word to array
                   if matches == splitlist: # if the arrays match ie everyword is in the dictionary add to possible arrays
                    possabilities.append(key) # adds the key to the array
                    plaintexts.append(decrypt) # adds the plaintext to the array
                    sentance.append(possiblewords) # adds the sentance to the array

              i=i+1

          print ("The possible keys are ", possabilities) # prints the keys
          print ("The possible plaintext are ", plaintexts) # prints the plaintext
          print ("The possible sentances are ", sentance) # prints the sentance
          
      

if __name__ == '__main__':
  print("This is a tool to crack a ciphertext with an unknown key")
  print("This tool will use all possiblities of keys within modular 26 (a-z)")
  print("Note this code will take a few mins to run as it runs through all possabilities\n")
  main() 
