create table webapp.users
(
    id int auto_increment primary key,
    nomeCompleto   varchar(255)         not null,
    dataNascimento varchar(10)          not null,
    email          varchar(255)         not null,
    username       varchar(255)         not null,
    password       varchar(255)         not null,
    isAdmin        tinyint(1) default 0 not null,
    isBanned       tinyint(1) default 0 not null
)ENGINE=innoDB;

create table webapp.games
(
    id int auto_increment primary key,
    resultado    enum ('victory', 'defeat'),
    dataHora     datetime                  ,
    idUtilizador int              ,
    FOREIGN KEY (idUtilizador) REFERENCES webapp2.users(id)
)ENGINE=innoDB;

insert into webapp.users(id, nomeCompleto, dataNascimento, email, username, password, isAdmin, isBanned) VALUES (1, 'Administrator', '2000-00-00', 'admin@localhost', 'admin', '240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9', 1, 0);
insert into webapp.users(id, nomeCompleto, dataNascimento, email, username, password, isAdmin, isBanned) VALUES (2, 'User', '2000-00-00', 'user@localhost', 'user', 'e606e38b0d8c19b24cf0ee3808183162ea7cd63ff7912dbb22b5e803286b4446', 0, 0);