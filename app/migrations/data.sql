INSERT INTO `currencies` (`id`, `name`, `code`, `symbol`, `delimiter`, `cseparator`, `position`, `is_integer`) VALUES
(1, 'American Dollar', 'USD', '$ ', ',', '.', 'left', 0),
(2, 'Nuevo Sol', 'PEN', 'S/.', ' ', ',', 'left', 0),
(3, 'Pound', 'GBP', '&pound; ', NULL, ',', 'left', 0),
(4, 'Euro', 'EUR', ' &euro;', '.', ',', 'right', 0),
(5, 'Japanese Yen', 'JPY', '&yen; ', '', '', 'left', 1);


INSERT INTO `languages` (`id`, `english_name`, `orig_name`) VALUES
(1, 'English', 'English'),
(2, 'Spanish', 'Español');


INSERT INTO `visibilities` (`id`, `name`) VALUES
(1, 'Public'),
(2, 'Private');
