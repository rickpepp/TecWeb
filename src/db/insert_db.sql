-- -----------------------------------------------------
-- secure_user
-- -----------------------------------------------------
GRANT SELECT, INSERT, UPDATE ON `tinkleart`.* TO 'secur_user'@'localhost';

INSERT INTO `categoria` (`idcategoria`,`nomecategoria`,`imgcategoria`) VALUES 
(1,'Bricolage','Bricolage.png'),
(2,'Bigiotteria','Bigiotteri.png'),
(3,'Falegnameria','Falegnameria.png'),
(4,'Tecnologia','Tecnologia.png'),
(5,'Trucco Casa','TruccoCasa.png');

ALTER TABLE `categoria`
MODIFY `idcategoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

INSERT INTO `persona` (`idpersona`,`nome`,`cognome`,`password`,`salt`,`email`,`descrizione`,`imgpersona`) VALUES 
-- Mario Rossi Password: 123Ciao%
-- Gianni Pinotto Password: 321Ciao#
-- Peppino Colorato Password: SonoPeppino3%
(1,'Mario','Rossi','d91784acc4c4a4e32fd3bbd05b3ac09b970b6429691dd43314758b8839b06c2c2e93856ca83f326f7c0996a3adcfd10cbfbcab6b6e81dce55164b6b176bff4cd','d75a443d44b75f9466cfa214c62b17d40ab9a4cfa532de60e868c0110f9899e8ad85853fe8673ae56d50d23ef102546efc0cf6078cf9c4e34db231056c1403d7','mariorossi@ciao.it','',''),
(2,'Gianni','Pinotto','79dc1ddab7f962108498339ba45be0805163bb6ee7e321f458ef2253fad560288ca890ebeef31f462091e5e6bbdfce1906422c53083f0352486b438754ac77b4','dae8ab427f428498c264cd486fc9cbcf42f9362c952731871e1c8bcf02dc23aa85aae45f002e281e8bfb94b5a0f546771df1100dbb28c1891ce9b16d2ad591c9','giannipinotto@ciao.it','Sono simpatico',''),
(3,'Peppino','Colorato','79d811b616c1a693573d132982455263e74afbb2e8286761588a82c198e81f97e3a141a9f7de0c5c969e05b1ed660e051280c226e71f80eb603caffeb15200dc','0d35243493254ea7214c5c872ee95f85d3a701e58e067ea25033d0c30f93d23be5e7fe0a4baaafde98c89ef7d0c011d03c71c98d5292adcd051dbedada461386','peppinocolorato@ciao.it','','peppino.jpg');

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