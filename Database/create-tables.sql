CREATE TABLE USER (
	AccountID	INT AUTO_INCREMENT,
	Fname		VARCHAR(25) NOT NULL,
	Lname		VARCHAR(25) NOT NULL,
	UserName	VARCHAR(25) NOT NULL,
	Password	VARCHAR(1000) NOT NULL,
	Category	INT NOT NULL DEFAULT 0,
	Phone		CHAR(10),
	Email		VARCHAR(40),
	PRIMARY KEY	(AccountID)
);

CREATE TABLE PRODUCT (
	ProductID	INT AUTO_INCREMENT,
	Name		VARCHAR(50) NOT NULL,
	Price		DECIMAL(10, 2) NOT NULL,
	Num	        INT NOT NULL,
	Category	VARCHAR(50) NOT NULL,
	Description	VARCHAR(200),
	Image		VARCHAR(50),
	Removed		INT NOT NULL DEFAULT 0,	
	PRIMARY KEY (ProductID)
);

CREATE TABLE PURCHASED_ORDER (
	OrderID		INT AUTO_INCREMENT,
	AccountID	INT NOT NULL,
	PurchaseDate	DATE NOT NULL,
	TotalPrice	DECIMAL(10, 2) NOT NULL,
	Comments	VARCHAR(200),
	PRIMARY KEY (OrderID),
	FOREIGN KEY (AccountID) REFERENCES USER(AccountID) ON DELETE CASCADE
);

CREATE TABLE ORDER_DETAIL (
	OrderID		INT,
	ProductID	INT,
	Name		VARCHAR(50) NOT NULL,
	PurchasedPrice		DECIMAL(10, 2) NOT NULL,
	Num 	    INT NOT NULL,
	PRIMARY KEY (OrderID, ProductID),
	FOREIGN KEY (OrderID) REFERENCES PURCHASED_ORDER(OrderID) ON DELETE CASCADE
);

CREATE TABLE CART_OWN_PRODUCT (
	AccountID	INT,
	ProductID	INT,
	Num		INT NOT NULL,
	PRIMARY KEY (AccountID, ProductID),
	FOREIGN KEY (AccountID) REFERENCES USER(AccountID) ON DELETE CASCADE,
	FOREIGN KEY (ProductID) REFERENCES PRODUCT(ProductID) ON DELETE CASCADE
);