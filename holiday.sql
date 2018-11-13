-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` varchar(20) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `permission` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`,`permission`) VALUES
('123456','lyj',md5('hr'),0),
('234567','test-1',md5('hr'),3),
('345678','test-2',md5('hr'),3),
('456789','test-3',md5('hr'),3),
('567890','test-4',md5('hr'),3),
('678901','test-5',md5('hr'),3);
INSERT INTO `users` (`user_id`, `username`, `password`,`permission`) VALUES
('000000','王欢',md5('hr'),3);


CREATE TABLE `holiday` (
  `name` int(11) NOT NULL,
  `initdate` DATE NOT NULL,
  `indate` DATE NOT NULL,
  `Companyage` int(11) NOT NULL,
  `Sumage` int(11) NOT NULL,
  `Sumday` int(11) NOT NULL,
  `Lastyear` int(11) NOT NULL,
  `Thisyear` int(11) NOT NULL,
  `Bonus` int(11) NOT NULL,
  `Used` int(11) NOT NULL,
  `Rest` int(11) NOT NULL

) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*
INSERT INTO `users` (`id`, `username`, `password`, `email`, `firstname`, `lastname`, `phone`, `gender`) VALUES
(1, 'adminknst', '$2y$10$yfi5nUQGXUZtMdl27dWAyOd/jMOmATBpiUvJDmUu9hJ5Ro6BE5wsK', 'admin@admin.com', 'john', 'doe', '80789998', 1);
*/

ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);




CREATE TRIGGER countSumday BEFORE INSERT ON `holiday`
FOR EACH ROW
BEGIN
    set new.Sumday=datediff('2009-10-11',now());
END;

CREATE TRIGGER countSumday BEFORE INSERT ON `holiday`
FOR EACH ROW
BEGIN
    NEW.indate=now();
END;

New.time=DATEDIFF(endtime,begintime)
New.Sumday=DATEDIFF(now(),NEW.indate)


CREATE TABLE `notice` (
  `Pubtime` date NOT NULL,
  `username` varchar(255) NOT NULL,
  `contents` varchar(2550) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;