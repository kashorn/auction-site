-- __________________  Create Tables Fresh __________________
CREATE TABLE PERSON (
	USER_ID INTEGER PRIMARY KEY,
	USERNAME VARCHAR(40) NOT NULL,
	LAST_NAME VARCHAR(96) NOT NULL,
	FIRST_NAME VARCHAR(96) NOT NULL,
	EMAIL_ADDRESS VARCHAR(255) NOT NULL,
	USER_PHOTO bytea );

CREATE TABLE ADDRESS (
	ADDRESS_ID INTEGER PRIMARY KEY,
	USER_ID INTEGER NOT NULL,
	KIND INTEGER NOT NULL,
	NAME VARCHAR(255) NOT NULL,
	STREET_1 VARCHAR(100) NOT NULL,
	STREET_2 VARCHAR(100),
    CITY VARCHAR(75) NOT NULL,
    STATE VARCHAR(2) NOT NULL,
    ZIP INTEGER NOT NULL  );

CREATE TABLE ADDRESS_TYPE (
	ADDRESS_TYPE_ID INTEGER PRIMARY KEY,
	NAME VARCHAR(40) NOT NULL );

CREATE TABLE BILLING_INFO (
    BILLING_INFO_ID INTEGER PRIMARY KEY,
	USER_ID INTEGER NOT NULL,
	LAST_NAME VARCHAR(96) NOT NULL,
	FIRST_NAME VARCHAR(96) NOT NULL,
	CARD_TYPE INTEGER NOT NULL,
	CARD_NUMBER VARCHAR(19) NOT NULL,
	EXP_MONTH INTEGER NOT NULL,
	EXP_DAY INTEGER NOT NULL,
	SECURITY_CODE INTEGER NOT NULL ); 

CREATE TABLE CARD_TYPE (
	CARD_TYPE_ID INTEGER PRIMARY KEY,
	CARD_NAME VARCHAR(20) NOT NULL );

CREATE TABLE USER_REVIEW (
	USER_REVIEW_ID INTEGER PRIMARY KEY,
	USER_ID_FOR INTEGER NOT NULL,
	USER_ID_BY INTEGER NOT NULL,
	RELIABILITY INTEGER NOT NULL,
	HONESTY INTEGER NOT NULL,
	TIMELINESS INTEGER NOT NULL,
	OVERALL INTEGER NOT NULL,
	BUY_AGAIN BOOLEAN NOT NULL );

CREATE TABLE LOGIN (
	USER_ID INTEGER PRIMARY KEY,
	PWD_HASH VARCHAR(255) NOT NULL );

CREATE TABLE AUCTION (
	AUCTION_ID INTEGER PRIMARY KEY,
	STATUS INTEGER NOT NULL,
	SELLER INTEGER NOT NULL,
    CURRENT_BID_ID INTEGER,
	STARTING_BID NUMERIC(7,2),
	RESERVE_PRICE NUMERIC(7,2),
	OPEN_TIME TIMESTAMP NOT NULL,
	CLOSE_TIME TIMESTAMP NOT NULL,
	ITEM_CATEGORY INTEGER,
	ITEM_NAME VARCHAR(78),
	ITEM_DESCRIPTION TEXT,
	ITEM_CONDITION INTEGER NOT NULL,
	ITEM_PHOTO bytea,
	PAID BOOLEAN );

CREATE TABLE AUCTION_STATUS (
	AUCTION_STATUS_ID INTEGER PRIMARY KEY,
	NAME VARCHAR(20) NOT NULL );

CREATE TABLE ITEM_CATEGORY (
	ITEM_CATEGORY_ID INTEGER PRIMARY KEY,
	NAME VARCHAR(78) NOT NULL );

CREATE TABLE ITEM_CONDITION (
	ITEM_CONDITION_ID INTEGER PRIMARY KEY,
	NAME VARCHAR(20) NOT NULL );

CREATE TABLE BID (
	BID_ID INTEGER PRIMARY KEY,
	BIDDER INTEGER NOT NULL,
	AUCTION INTEGER NOT NULL,
	BID_TIME TIMESTAMP NOT NULL,
	AMOUNT NUMERIC(9,2) NOT NULL );

CREATE TABLE WATCHED_ITEMS (
    WATCHED_ITEMS_ID INTEGER PRIMARY KEY,
	USER_ID INTEGER NOT NULL, 
	ITEM_ID INTEGER NOT NULL );


-- __________________  Create Indecies __________________
CREATE INDEX AUCTION_STATUS_NAME_INDEX ON AUCTION_STATUS (NAME);
CREATE INDEX ITEM_CATEGORY_NAME_INDEX ON ITEM_CATEGORY (NAME);
CREATE INDEX AUCTION_STATUS_INDEX ON AUCTION (STATUS);
CREATE INDEX AUCTION_SELLER_INDEX ON AUCTION (SELLER);
CREATE INDEX AUCTION_ITEM_CATEGORY_INDEX ON AUCTION (ITEM_CATEGORY);
CREATE INDEX BID_AUCTION_INDEX ON BID (AUCTION);
CREATE INDEX BID_BIDDER_INDEX ON BID (BIDDER);


-- __________________  Add Foreign Keys __________________
ALTER TABLE ADDRESS ADD FOREIGN KEY (USER_ID) REFERENCES PERSON(USER_ID) DEFERRABLE;
ALTER TABLE ADDRESS ADD FOREIGN KEY (KIND) REFERENCES ADDRESS_TYPE(ADDRESS_TYPE_ID) DEFERRABLE;
ALTER TABLE BILLING_INFO ADD FOREIGN KEY (USER_ID) REFERENCES PERSON(USER_ID) DEFERRABLE;
ALTER TABLE BILLING_INFO ADD FOREIGN KEY (CARD_TYPE) REFERENCES CARD_TYPE(CARD_TYPE_ID) DEFERRABLE;
ALTER TABLE USER_REVIEW ADD FOREIGN KEY (USER_ID_FOR) REFERENCES PERSON(USER_ID) DEFERRABLE;
ALTER TABLE USER_REVIEW ADD FOREIGN KEY (USER_ID_BY) REFERENCES PERSON(USER_ID) DEFERRABLE;
ALTER TABLE LOGIN ADD FOREIGN KEY (USER_ID) REFERENCES PERSON(USER_ID) DEFERRABLE;
ALTER TABLE AUCTION ADD FOREIGN KEY (SELLER) REFERENCES PERSON(USER_ID) DEFERRABLE;
ALTER TABLE AUCTION ADD FOREIGN KEY (STATUS) REFERENCES AUCTION_STATUS(AUCTION_STATUS_ID) DEFERRABLE;
ALTER TABLE AUCTION ADD FOREIGN KEY (ITEM_CATEGORY) REFERENCES ITEM_CATEGORY(ITEM_CATEGORY_ID) DEFERRABLE;
ALTER TABLE AUCTION ADD FOREIGN KEY (CURRENT_BID_ID) REFERENCES BID(BID_ID) DEFERRABLE;
ALTER TABLE AUCTION ADD FOREIGN KEY (ITEM_CONDITION) REFERENCES ITEM_CONDITION(ITEM_CONDITION_ID) DEFERRABLE;
ALTER TABLE BID ADD FOREIGN KEY (BIDDER) REFERENCES PERSON(USER_ID) DEFERRABLE;
ALTER TABLE BID ADD FOREIGN KEY (AUCTION) REFERENCES AUCTION(AUCTION_ID) DEFERRABLE;
ALTER TABLE WATCHED_ITEMS ADD FOREIGN KEY (USER_ID) REFERENCES PERSON(USER_ID) DEFERRABLE;



-- __________________  Create Sequence Generator __________________
CREATE SEQUENCE ADDRESS_SEQ;
CREATE SEQUENCE AUCTION_ID_SEQ;
CREATE SEQUENCE BID_ID_SEQ;
CREATE SEQUENCE LOGIN_ID_SEQ;
CREATE SEQUENCE USER_ID_SEQ;
CREATE SEQUENCE USER_REVIEW_ID_SEQ;
CREATE SEQUENCE WATCHED_ITEMS_ID_SEQ;
