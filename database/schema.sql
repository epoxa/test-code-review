 -- I believe lang and collation are already configured at database level

CREATE TABLE project ( -- Some guys prefer plural *projects* but *project* is ok for me!
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP -- TIMESTAMP data type is better for this
) Engine=InnoDB;

CREATE TABLE task (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    project_id INT NOT NULL, -- It would be nice to add an external key for the field to ensure referential integrity
    title VARCHAR(255) NOT NULL,
    status VARCHAR(16) NOT NULL, -- Consider to use ENUM for this
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP -- TIMESTAMP data type is better
) Engine=InnoDB;
