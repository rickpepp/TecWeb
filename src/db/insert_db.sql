INSERT INTO `categoria` (`idcategoria`,`nomecategoria`,`imgcategoria`) VALUES 
(1,'Bricolage','Bricolage.png'),
(2,'Bigiotteria','Bigiotteri.png'),
(3,'Falegnameria','Falegnameria.png'),
(4,'Tecnologia','Tecnologia.png'),
(5,'Trucco Casa','TruccoCasa.png');

ALTER TABLE `categoria`
MODIFY `idcategoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

INSERT INTO `persona` (`idpersona`,`nome`,`cognome`,`password`,`email`,`descrizione`,`imgpersona`) VALUES 
(1,'Mario','Rossi','123Ciao$','mariorossi@ciao.it','',''),
(2,'Gianni','Pinotto','321Ciao#','giannipinotto@ciao.it','Sono simpatico',''),
(3,'Peppino','Colorato','SonoPeppino3%','peppinocolorato@ciao.it','','peppino.jpg');

ALTER TABLE `persona`
MODIFY `idpersona` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

INSERT INTO `post` (`idpost`,`imgpost`,`testopost`,`persona`) VALUES
(1,'Post1','Questo è il mio bellissimo lavoro',2),
(2,'Post2','Guardate quanto è bello il mio vaso. Sono molto felice :)',3);

ALTER TABLE `post`
MODIFY `idpost` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

INSERT INTO `post_ha_categoria` (`post`,`categoria`) VALUES
(1,3),
(2,1);

INSERT INTO `commento` (`idcommento`,`persona`,`post`,`testocommento`,`visualizzato`) VALUES
(1,1,1,'Molto bello, grandissimo Gianni!!! ;)',0),
(2,3,1,'A me non piace, mi dispiace signor Gianni :(',0),
(3,2,2,'Lo vorrei anche io!!!!',1);

ALTER TABLE `commento`
MODIFY `idcommento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

INSERT INTO `like` (`post`,`persona`,`visualizzato`) VALUES
(1,1,0),
(1,2,0),
(2,2,0);

INSERT INTO `hashtag` (`idhashtag`,`nomehashtag`) VALUES
(1,'VivaLaVita'),
(2,'BricolageLaMiaVita');

ALTER TABLE `hashtag`
MODIFY `idhashtag` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

INSERT INTO `hashtag_ha_post` (`hashtag`,`post`) VALUES
(1,1),
(2,1),
(2,2);

INSERT INTO `segui_persona` (`personaseguita`,`personasegue`,`visualizzato`) VALUES
(1,2,0),
(2,1,1),
(3,1,0);

INSERT INTO `segui_categoria` (`persona`,`categoria`) VALUES
(1,5),
(2,5),
(3,1),
(2,2),
(3,3);