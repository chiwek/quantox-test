# quantox-test 
I wanted this test to be a learning process for myself as well. 

In that light, I used libraries I've never used before(probably nobody did) to try and build a new app with unknown tools in 5 hours. 

The end result is not a very bright solution, but surely is an original one.. :) 

Here's the users table sql

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `email` varchar(120) NOT NULL,
  `password` varchar(120) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;



The fact that database parameters are stored in the public/index.php file of the project clearly states that I have failed my task :)

