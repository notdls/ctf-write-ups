__Bugs\_BunnyCTF-2017__

__Category__ - Web | __Points__ - 80

__Description:__

PHP for noobs :p ?  
Maybe not this time :D  
http://34.253.165.46/SimplePhp/index.php  
source : [http://34.253.165.46/SimplePhp/index.txt](https://github.com/notdls/ctf-write-ups/blob/master/Bugs_BunnyCTF-2017/web/SimplePHP-80/flag.php)  
Author: TnMch

__Useful Resources:__

https://stackoverflow.com/questions/2715654/what-does-dollar-dollar-or-double-dollar-mean-in-php

http://php.net/manual/en/language.variables.variable.php


__Solution:__

Before reading this, I __highly__ recommend you read the stackoverflow post above about variable variables. This was a completely new concept to me because I hadn't done too much PHP in the past and it helped a lot. Also, read the index.php code as this was given to us to help solve the challenge.

So, after reading index.php, we know we need to meet the following conditions:
  - We have to send a POST request
  - There __must__ be a flag parameter in the POST data

This is where the main issue arises, by setting a flag parameter in POST you will be over-writing the $flag variable which holds the real flag. So, we must find a way to either not change the flag variable or move it into another variable that is echo'd.

In the first loop we see __$$key = $$value__, this means we can assign one variable the value of another. In this case, we want to assign __$\_200__ the value of __$flag__, this way it will be echo'd at the end of the file.

We can do this by setting the following GET data in the URL: __index.php?\_200=flag__. But, we also need to set the __flag__ parameter in POST, it doesn't matter what value you give it.

In the end I just wrote a simple [python script](https://github.com/notdls/ctf-write-ups/blob/master/Bugs_BunnyCTF-2017/web/SimplePHP-80/solution.py) to perform the request.
```
import requests
s = requests.Session()
post = s.post("http://34.253.165.46/SimplePhp/index.php?_200=flag", data={"flag":"someText"})
print(post.text)
```
Upon running this we get the following output which includes the flag.
```
This is your flag : someText
Bugs_Bunny{This_Isnt_The_Real_Flag}
```

