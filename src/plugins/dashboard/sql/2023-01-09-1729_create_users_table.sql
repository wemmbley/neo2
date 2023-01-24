create table if not exists users(
    id integer auto_increment primary key ,
    login varchar(64) unique not null ,
    password varchar(64) not null ,
    token varchar(64) unique
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;