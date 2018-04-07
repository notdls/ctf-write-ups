SunshineCTF - Marceau
-------------------------
**Category:** Web

**Points:** 100

##### Description:
```
Hey my friend tells me that the flag is in this site's source code. Idk how to read that though, lol (ðŸ…±ï¸retty lame tbh ðŸ˜‚)

http://marceau.web1.sunshinectf.org

Author: charlton

Hint 2018-04-06 00:20 UTC: There are many different types of MIMEs, but only a handful were truly legendary...

```
Upon visiting the site we're prompted with the following text:
```
You specifically want my PHP source. Why did you accept anything else?
```
They give us two pretty big hints in this text, them being **PHP** and **accept**, from here I assumed we had to manipulate the ``Accept`` header in the request, and it worked! By changing our header to ``Accept: text/php,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8`` it revealed the PHP code along with the flag:
```
<?php
// sun{45k_4nd_y3_5h411_r3c31v3} (nice work!)

// */* won't work here- you'll have to be more assertive.
if(strpos($_SERVER['HTTP_ACCEPT'], "text/php") === false)
  echo "<marquee><h3>You specifically want my PHP source. Why did you accept anything else?</h3></marquee>";
else
  show_source(__FILE__);
?>
```
