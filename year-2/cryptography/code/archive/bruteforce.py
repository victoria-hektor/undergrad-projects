def egcd(a, b):  # funtion to find the highest common factor (hcf)
  x,y, u,v = 0,1, 1,0 # sets values
  while a != 0: # while loop
    q, r = b//a, b%a  # sets Q = B floor divided A and sets R = A modulur A
    m, n = x-u*q, y-v*q # sets m = x-u multipled Q and sets N = y-v multiplied Q
    b,a, x,y, u,v = a,r, u,v, m,n  # sets values
  gcd = b # set gcd = b
  return gcd, x, y # returns the values 

def modinv(a, m): # Finding the inverse of the first key 
  gcd, x, y = egcd(a, m) #calls the function to find the hcf
  if gcd != 1: # if statement (not used anymore)
    return None # modular inverse does not exist (no longer needed)
  else: # else statement
    return x % m # X modular M (% is modular)
  
def main(): # main funtion that does the brute force
          text = 'GRKTPUCRITBKPERWAVUXUCK' # our ciphertext 
          key1 = [1, 3, 5, 7, 9, 11, 15, 17, 19, 21, 23, 25] # array of key A
          key2 = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10,11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25]# array of key B
          i,j = 0,0 # iterative values
          key1len=len(key1) # sets the length of key A
          key2len=len(key2) # sets the length of key B
          possibilitys = [] # decalres an array for the possible keys
          plaintexts = []   # declares an arrat for the possible plaintexts
          for i in range(key2len): # for loop to try every possiblity in key B
              g = key2[i] # sets the current value from key B
              for j in range(key1len): # for loop to try every possiblity in key A
                 f = key1[j] # sets the current value from key A
                 j = j+1 # itterative value
                 key = [f, g] # combines key A and key B to be used in the decrypt function
                 fstr = int(f) # converts F from str to int
                 gstr = int(g) # converts G from str to int
                 decrypt = (''.join([ chr((( modinv(key[0], 26)* #mod inv of key A in mod 26 multiplied by
                           (ord(c) - ord('A') - key[1])) % 26) + ord('A')) # converts the letter to numbers and subtracts key B into mod 26
                            for c in 'GRKTPUCRITBKPERWAVUXUCK' ])) # does the decrypt function on each character in the ciphertext
                 l = 0 # sets value
                 crypto = decrypt.lower() # converts the plaintext to lowercase
                 for l in range(24): # loops through the length of the plaintext so i can be checked in the dictionary
                    p1 = crypto[:l] # first part of the plain text
                    p2 = crypto[l:] # second part of the plain text
                    with open("dictionary.txt", encoding="latin-1") as file: # opens the dictionary using latin-1 encoding
                          word = ""
                          for readline in file: # for loop to remove the new lines
                              line_strip = readline.replace('\n', "") #replaces the newlines
                              word += line_strip # combines the word without the newline
                          if ((p1 in word) and (p2 in word)):# checks if the decrypted text is in the dictionary
                              possibilitys.append(key) # adds to key to the possible keys
                              plaintexts.append(decrypt) # adds the plaintext to the array

          print ("The possible keys are ", possibilitys) # prints the possible keys back to the user
          print ("the possible plaintext is ", plaintexts) # prints out the possible ciphertexts checked against the dictionary

main() # calls the main function
