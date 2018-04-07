SunshineCTF - Evaluation
-------------------------
**Category:** Web

**Points:** 50

##### Description:
```
Evaluate your life.
How are you doing, and are you doing the best you can possibly do?
Look deeper within yourself, beyond the obvious.
Look at the source of it all.

Also, here's a PHP challenge.

http://evaluation.web1.sunshinectf.org

Author: hackucf_levi
```
Upon visiting the site we're prompted with the following code:
```
<?php 

include "flag.php";
error_reporting(0);
show_source(__FILE__);
$a = @$_REQUEST['hello'];
eval("var_dump($a);");

?>
```
From the following code we can assume that the flag is stored in ``flag.php``, also we know its supressing errors by adding the ``@`` symbol infront of ``$_REQUEST['hello'];``, regardless we know we know our attack vector is the parameter ``hello`` and that it's passed inside the ``eval()`` function without being parsed or formatted which is a big red flag. 

After sending a GET request with ``?hello=$flag`` we get the response ``string(4) "Nope"``so we can assume that the flag isn't stored in the variable but inside the file. 

After this I copied the code locally so I could easier debug it (with error reporting so I could break out of the ``var_dump``. After some tweaking I managed to use the ``show_source`` function to reveal the flag using the payload ``$flag); show_source('flag.php'`` revealing the source code and the flag:
```
<?php 
$flag ="Nope";

// sun{c0mm4nD_1Nj3cti0n_i5_E4sY};

?>
```
