CREATE DATABASE IF NOT EXISTS smartcard_db;
USE smartcard_db;

CREATE TABLE IF NOT EXISTS Administrator (
  admin_id CHAR(11) NOT NULL,
  phone_no VARCHAR(15) NOT NULL,
  email VARCHAR(50) NOT NULL,
  al_name VARCHAR(20) NOT NULL,
  af_name VARCHAR(20) NOT NULL,  
  passwd VARCHAR(30) NOT NULL,
  PRIMARY KEY (admin_id)
);

CREATE TABLE IF NOT EXISTS Venue (
  venue_id VARCHAR(20) NOT NULL,
  college VARCHAR(15) NOT NULL,
  device_id VARCHAR(50) NOT NULL,
  PRIMARY KEY (venue_id, college)
);

CREATE TABLE IF NOT EXISTS Lecturer (
  ll_name VARCHAR(20) NOT NULL,
  lf_name VARCHAR(20) NOT NULL,
  lecturer_id CHAR(11) NOT NULL,
  lecturer_email VARCHAR(50) NOT NULL, 
  phone_no VARCHAR(15) NOT NULL,
  department VARCHAR(10) NOT NULL,  
  passwd VARCHAR(30) NOT NULL,
  PRIMARY KEY (lecturer_id)
);

CREATE TABLE IF NOT EXISTS Student (
  reg_no CHAR(11) NOT NULL,
  Programme VARCHAR(10) NOT NULL,
  sl_name VARCHAR(30) NOT NULL,
  sf_name VARCHAR(30) NOT NULL,
  college VARCHAR(8) NOT NULL,
  Year_of_study INT NOT NULL, 
  Gender VARCHAR(6) NOT NULL,
  sem1_pay BIT(1) NOT NULL,
  sem2_pay BIT(1) NOT NULL,
  img_dir VARCHAR(255) NOT NULL,
  admin_id CHAR(11) NOT NULL,
  PRIMARY KEY (reg_no),
  FOREIGN KEY (admin_id) REFERENCES Administrator(admin_id)
);

CREATE TABLE IF NOT EXISTS Student_accesses (
  timestamp DATE NOT NULL,
  reg_no CHAR(11) NOT NULL,
  venue_id VARCHAR(20) NOT NULL,
  college VARCHAR(15) NOT NULL,
  PRIMARY KEY (timestamp),
  FOREIGN KEY (reg_no) REFERENCES Student(reg_no),
  FOREIGN KEY (venue_id, college) REFERENCES Venue(venue_id, college)
);

CREATE TABLE IF NOT EXISTS Student_rfid_id (
  rfid_id VARCHAR(20) NOT NULL,
  reg_no CHAR(11) NOT NULL,
  PRIMARY KEY (rfid_id),
  FOREIGN KEY (reg_no) REFERENCES Student(reg_no)
); 

INSERT INTO Administrator (admin_id, phone_no, email, al_name, af_name, passwd)
VALUES ('20100000000', '0766830896', 'adminud@gmail.com', 'ud', 'admin', 'admin');

INSERT INTO Student (reg_no, Programme, sl_name, sf_name, college, Year_of_study, sem1_pay, sem2_pay, img_dir, admin_id, Gender)
VALUES ('20190411297', 'CEIT', 'Saitoria', 'Saroni', 'COICT', 4, 1, 0, 'profilePicture/20190411297', '20100000000', 'Male');

INSERT INTO Student (reg_no, Programme, sl_name, sf_name, college, Year_of_study, sem1_pay, sem2_pay, img_dir, admin_id, Gender)
VALUES ('20190408124', 'CEIT', 'Msangi', 'Daniel', 'COICT', 4, 1, 1, 'profilePicture/20190408124', '20100000000', 'Male');

INSERT INTO Student (reg_no, Programme, sl_name, sf_name, college, Year_of_study, sem1_pay, sem2_pay, img_dir, admin_id, Gender)
VALUES ('20190410399', 'CEIT', 'Ogigo', 'Calvince', 'COICT', 4, 1, 0, 'profilePicture/20190410399', '20100000000', 'Male');

INSERT INTO Student (reg_no, Programme, sl_name, sf_name, college, Year_of_study, sem1_pay, sem2_pay, img_dir, admin_id, Gender)
VALUES ('20190412812', 'CEIT', 'Wambura', 'Machuche', 'COICT', 4, 1, 1, 'profilePicture/20190412812', '20100000000', 'Male');

INSERT INTO Student (reg_no, Programme, sl_name, sf_name, college, Year_of_study, sem1_pay, sem2_pay, img_dir, admin_id, Gender)
VALUES ('20190409312', 'CEIT', 'Mwaya', 'Jackson', 'COICT', 4, 1, 1, 'profilePicture/20190409312', '20100000000', 'Male');

INSERT INTO Venue (venue_id, college, device_id)
VALUES ('D01', 'COICT', 'D01');

INSERT INTO Venue (venue_id, college, device_id)
VALUES ('B210', 'COICT', 'B210');

INSERT INTO Venue (venue_id, college, device_id)
VALUES ('C4', 'COICT', 'C4');

INSERT INTO Venue (venue_id, college, device_id)
VALUES ('B201', 'COICT', 'B201');

INSERT INTO Student_rfid_id (rfid_id, reg_no)
VALUES ('AB123456789A', '20190409312');

INSERT INTO Student_rfid_id (rfid_id, reg_no)
VALUES ('AB123456789B', '20190410399');


INSERT INTO Lecturer (ll_name, lf_name, lecturer_id, lecturer_email, department, phone_no, passwd)
VALUES ('Maziku', 'Hellen', '20120110001', 'hellenmaziku@gmail.com', 'CSE', '0712345678', 'hellenmaziku');

INSERT INTO Lecturer (ll_name, lf_name, lecturer_id, lecturer_email, department, phone_no, passwd)
VALUES ('Lungo', 'Juma', '20120110002', 'jumalungo@gmail.com', 'CSE', '0713245678', 'jumalungo');

INSERT INTO Lecturer (ll_name, lf_name, lecturer_id, lecturer_email, department, phone_no, passwd)
VALUES ('Maiseli', 'Baraka', '20120210001', 'barakamaiseli@gmail.com', 'ETE', '0713235678', 'barakamaiseli');

INSERT INTO Lecturer (ll_name, lf_name, lecturer_id, lecturer_email, department, phone_no, passwd)
VALUES ('ud', 'admin', '20100000000', 'adminud@gmail.com', 'ADMIN', '0766830896', 'admin');
