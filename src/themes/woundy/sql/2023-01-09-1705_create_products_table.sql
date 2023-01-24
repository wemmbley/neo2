create table if not exists products(
    id integer auto_increment primary key,
    slug varchar(64) not null ,
    title varchar(64) not null ,
    description varchar(256),
    full_description text not null ,
    image_url varchar(256) not null
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;