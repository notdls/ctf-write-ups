SunshineCTF - Home Sweet Home
-------------------------
**Category:** Web

**Points:** 150

##### Description:
```
Looks like this site is doing some IP filtering.
That's very FORWARD thinking of them.

Have fun!

http://web1.sunshinectf.org:50005

Author: hackucf_levi
```
Upon visiting the site we're prompted with the following text:
```
14.212.11.223This IP address is not authorized
```
From this we can see that they site has indeed put restrictions based on a user's IP, and the description gives us a big hint by saying **FORWARD**, from here I assumed that they were using X-Forwarded-For to filtering IPs, so I added ``X-Forwarded-For: 127.0.0.1`` to my header and sent the request again and bingo we get the flag!
```
127.0.0.1Here's your flag: sun{Th3rEs_n0_pl4cE_l1kE_127.0.0.1}
```
