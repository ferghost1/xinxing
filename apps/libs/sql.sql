create table acc
(
    id VARCHAR(30),
    user VARCHAR(40) not null,
    pass VARCHAR(32) not null,
    type VARCHAR(20) not null,
    timecreate DateTime,
    active VARCHAR(10) not null,
    PRIMARY KEY(id,user)
);
create table detailacc-- chi tiet acc
(
    id VARCHAR(30),
    idacc VARCHAR(30) not null,
    name VARCHAR(50),
    identitycard VARCHAR(20),
    maill VARCHAR(50),
    phonenumber VARCHAR(30),
    linkimg VARCHAR(100),
    bank VARCHAR(100), -- Tên ngân hàng
    bankaccountname VARCHAR(100), -- Tên chủ khoản
    bankaccountnumber VARCHAR(100), -- Số tài khoản
    PRIMARY KEY(id)
);
create table relationshipacc-- moi quan he
(
    id VARCHAR(30),
    dadacc VARCHAR(30) not null,
    children VARCHAR(30) not null,
    PRIMARY KEY(id)
);

create table historyproduct -- lich su giao dich
(
    id VARCHAR(30),
    idacc VARCHAR(30),
    name VARCHAR(70),
    note VARCHAR(1000),
    timecreate DateTime,
    PRIMARY KEY(id)
);
create table listproduct -- danh sach mat hang giao dich
(
    id VARCHAR(30),
    idhp VARCHAR(30) not null,
    name VARCHAR(70),-- ten
    price bigint,-- gia
    quantity bigint,-- so luong
    PRIMARY KEY(id)
);

create table setting
(
    id VARCHAR(30),
    accumulate bigint, -- mốc tích lũy
    percentcompanyreturn float, -- % số tiền công ty trích ra
    limitshare bigint, -- giới hạn tối đa một đơn vị đồng chia
    firstreturnshare float, -- % đồng chia lần 1, tái mua hàng
    nextreturnshare float, -- % đồng chia các lần tiếp , tái mua hàng
    limitreturnshare float, -- giới hạn đồng chia cao nhất
    levelf1return float, -- % số tiền nhận từ F1
    levelf2f5return float, -- % số tiền nhận từ F2 tới F5
    extractmax float, -- % số chiết khấu lớn nhất
    PRIMARY KEY(id)
);

create table settingreceived
(
    money bigint, -- mốc doanh số
    percentsend float, -- mức chiết khấu
    percentreceive float -- tổng chiết khấu
);

create table settingshare
(
    id VARCHAR(30),
    limitshare bigint,
    timecreate DateTime
);

create table settingshowshare
(
    timecreate DateTime,
    active VARCHAR(5)
);

INSERT INTO `setting`(id) VALUES (1);
INSERT INTO `detailacc`(`id`, `idacc`, `name`, `identitycard`, `maill`, `phonenumber`, `linkimg`) VALUES ('D88734A3AC4D00C998134048D352C6','5165D708197C54B11191D46DB741A5','demo','12346864','demo@gmail.com','0169999999','');
INSERT INTO `acc`(`id`, `user`, `pass`, `type`, `timecreate`, `active`) VALUES ('5165D708197C54B11191D46DB741A5','admin','21232f297a57a5a743894a0e4a801fc3','root',NOW(),'yes');

--chon cac tai khoan co doanh thu lon hon 11250000
SELECT acc.user,price.total 
FROM 
(SELECT acc.id,SUM(listproduct.price*listproduct.quantity) as total
FROM historyproduct,listproduct,acc
WHERE acc.id=historyproduct.idacc and listproduct.idhp=historyproduct.id and month(historyproduct.timecreate)=02 and year(historyproduct.timecreate)=2018
GROUP BY acc.user) 
as price,acc
WHERE price.total > 11250000 and acc.id= price.id
-- ban cai tien
SELECT FLOOR(price.total/11250000)
FROM 
(SELECT acc.id,SUM(listproduct.price*listproduct.quantity) as total
FROM historyproduct,listproduct,acc
WHERE acc.id=historyproduct.idacc and listproduct.idhp=historyproduct.id and month(historyproduct.timecreate)=02 and year(historyproduct.timecreate)=2018
GROUP BY acc.user) 
as price,acc
WHERE price.total >= 11250000 and acc.id= price.id