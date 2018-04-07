SunshineCTF - WorkshopHelper
-------------------------
**Category:** Scripting

**Points:** 300

##### Description:
```
Our workshop needs to figure out which gears to use in our mechs! Help us find those gears!

http://workshop.web1.sunshinectf.org

Note: All answers are integers!

Author: hackucf_vrael
```
Upon visiting the site we're prompted with the following text:
```
Hello there! Welcome to my workshop! I need to get some things done, can you help?

We are given a clue about which problem we need to solve and then we have to figure out which one the clues matches to. Once we do that, we then solve that problem and submit that solution. We have to be quick though, because we only have 10 seconds for each problem! Hit the start button once you are ready to begin!
```
Considering that this is in the scripting category and that the problems need to be solved, we can assume that this has to be automated. So I capture the requests to start a new game which looks like this:

##### Request
```
GET /start?answer=no_answer HTTP/1.1
Host: workshop.web1.sunshinectf.org
User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:59.0) Gecko/20100101 Firefox/59.0
Accept: application/json, text/plain, */*
Accept-Language: en-US,en;q=0.5
Accept-Encoding: gzip, deflate
Referer: http://workshop.web1.sunshinectf.org/
Authorization: 
Connection: close
```
##### Response 
```
HTTP/1.1 200 OK
Server: nginx
Date: Sat, 07 Apr 2018 03:17:58 GMT
Content-Type: application/json; charset=utf-8
Content-Length: 3286
Connection: close
X-Powered-By: Express
ETag: W/"cd6-BSiVzy+Z8oe7lzk8e6A+/6qOjpQ"

{"token":"269206a3-06fc-4c65-8765-1991d8c89aed","level":0,"questions":[{"id":"460967","name":"546919","class":"48996","question":"823372 / 38686","answers":[85,94,45,21]},{"id":"759","name":"13081","class":"425263","question":"927803 + 386384","answers":[1314187,1314219,1314231,1314171]},{"id":"270363","name":"106002","class":"45432","question":"980576 + 588171","answers":[]},{"id":"678967","name":"408305","class":"245316","question":"21116 * 970600","answers":[20495189644,20495189589,20495189600,20495189656]},{"id":"536291","name":"465354","class":"995509","question":"124657 - 200968","answers":[-76311,-76349,-76256,-76359]},{"id":"692793","name":"399288","class":"57867","question":"680958 / 163197","answers":[]},{"id":"446105","name":"768867","class":"771400","question":"254847 + 357381","answers":[]},{"id":"507906","name":"775367","class":"655408","question":"536505 % 55748","answers":[]},{"id":"858884","name":"944349","class":"448530","question":"610462 * 424765","answers":[]},{"id":"878833","name":"556269","class":"524965","question":"779820 + 363410","answers":[]},{"id":"768464","name":"737976","class":"729791","question":"211531 + 641761","answers":[853211,853204,853337,853292]},{"id":"601094","name":"606044","class":"838356","question":"626344 % 794154","answers":[]},{"id":"219552","name":"982064","class":"570380","question":"960577 - 334472","answers":[626105,626138,626165,626049]},{"id":"579750","name":"748284","class":"652686","question":"956424 * 708039","answers":[677185492465,677185492539,677185492483,677185492536]},{"id":"248155","name":"458552","class":"251812","question":"697693 - 518665","answers":[]},{"id":"284904","name":"893833","class":"432299","question":"546275 / 931940","answers":[-27,62,0,38]},{"id":"893846","name":"67783","class":"886335","question":"953731 / 53526","answers":[]},{"id":"786709","name":"981327","class":"330408","question":"257239 - 327602","answers":[-70289,-70395,-70363,-70460]},{"id":"995210","name":"592923","class":"324152","question":"151071 / 942764","answers":[31,0,-20,17]},{"id":"81876","name":"842603","class":"832361","question":"930466 / 721586","answers":[]},{"id":"503877","name":"927219","class":"838469","question":"34315 - 831072","answers":[-796708,-796779,-796756,-796757]},{"id":"953679","name":"979117","class":"955635","question":"695518 % 359173","answers":[336319,336320,336345,336299]},{"id":"471406","name":"35715","class":"346938","question":"410046 * 765825","answers":[]},{"id":"409428","name":"78539","class":"326402","question":"780946 % 518848","answers":[]},{"id":"808343","name":"3199","class":"533875","question":"341312 + 426469","answers":[767696,767781,767808,767821]},{"id":"947102","name":"760479","class":"564063","question":"870715 * 157275","answers":[136941701629,136941701625,136941701710,136941701592]},{"id":"10464","name":"205843","class":"458910","question":"501643 / 80938","answers":[]},{"id":"759769","name":"446300","class":"401275","question":"856209 - 729308","answers":[126981,126901,126958,126877]},{"id":"606656","name":"585411","class":"202624","question":"375346 + 187811","answers":[]},{"id":"927467","name":"549902","class":"354783","question":"269496 % 188479","answers":[81043,81000,80975,81017]}],"featureHintType":"name","featureHint":"458552"}
```

So we can see that the response is in json so the parsing/display is done client-side meaning we should be able to automate it with python using the requests and json packages. Also take note of the **featuteHintType** and **featureHint** values, as we need to solve the corresponding question (E.g. the question where the HintType is equal to the featureHint).

After this I captured what answering a question looked like:
##### Request
```
GET /submit?answer=45 HTTP/1.1
Host: workshop.web1.sunshinectf.org
User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:59.0) Gecko/20100101 Firefox/59.0
Accept: application/json, text/plain, */*
Accept-Language: en-US,en;q=0.5
Accept-Encoding: gzip, deflate
Referer: http://workshop.web1.sunshinectf.org/
Authorization: 269206a3-06fc-4c65-8765-1991d8c89aed
Connection: close
```
So, it looks like it shouldn't be too hard to automate so I put together a quick Python script to solve it.
```
import requests
import json
s = requests.Session()
start = True
while True:
	if start:
		qjson = json.loads(s.get("http://workshop.web1.sunshinectf.org/start?answer=no_answer").text)
		start = False
	token = qjson["token"]
	ftype = qjson["featureHintType"]
	fval = qjson["featureHint"]
	for q in qjson["questions"]:
		if q[ftype] == fval:
			print("Question found: "+q["question"])
			print("Token: "+token)
			answer = int(eval(q["question"]))
	s.headers.update({"Authorization":token})
	qjson = json.loads(s.get("http://workshop.web1.sunshinectf.org/submit?answer="+str(answer)).text)
	if "sun{" in str(qjson):
		print(str(qjson))
	print(str(qjson["result"]) + " | " + str(qjson["level"]))
```
After we solve 30 questions (reach level 30) we receive the flag!
```
'flag': 'sun{W3_g0t_the_g3ar5_w3_got_th3_p0w3r}'
```
