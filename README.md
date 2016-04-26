#Muncher
.sql file is in private folder

There are 2 php files that do the same thing (contact.php , contactSimple.php) , only one is being used.
I originally started out by wanting to use an abstract class called Savable and use it on all models that need to be persisted
I got carried away with making something I thought would be cool by using a database interface & database singleton class
then I realized you have like 50 of these to correct so probably don't want us to do to much with the PHP and also thought maybe you would dock me marks for making so complex for something as simple as subscribe and contact, So I made a much smaller file in contactSimple.php and tried to make it very small & get the job done , If thats enough for you then there is no need to look at contact.php and all the php files in /private/PHP/* . I almost deleted them all but couldn't bring myself to do it haha so left them in.
all the forms are pointing at contactSimple.php , but contact.php works also

GITHUB: https://github.com/AlexThomasWebElevate/Muncher

WORKING EXAMPLE: http://ec2-52-16-124-54.eu-west-1.compute.amazonaws.com/Muncher/
