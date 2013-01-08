/* $Id$ */
SET NAMES 'UTF8';
/* --------------------------------------------------------
  Structure of table "users"
-------------------------------------------------------- */
DROP SEQUENCE IF EXISTS users_id_seq;
CREATE SEQUENCE users_id_seq;
DROP TABLE IF EXISTS users CASCADE;
CREATE TABLE users (
	id integer DEFAULT nextval('users_id_seq'::text) NOT NULL,
	login varchar(32) 	DEFAULT '' NOT NULL,
	name varchar(64) 	DEFAULT '' NOT NULL,
	email varchar(255) 	DEFAULT '' NOT NULL,
	phone varchar(32)   DEFAULT NULL,
	position varchar(255) 	DEFAULT '' NOT NULL,
	rights varchar(64) 	DEFAULT '' NOT NULL,
	hosts varchar(255) 	DEFAULT '' NOT NULL,
	passwd varchar(255) 	DEFAULT '' NOT NULL,
	ntype smallint      DEFAULT NULL,
	lastlogindate integer 	DEFAULT 0  NOT NULL,
	lastloginip varchar(16) DEFAULT '' NOT NULL,
	failedlogindate integer DEFAULT 0  NOT NULL,
	failedloginip varchar(16) DEFAULT '' NOT NULL,
	deleted smallint	DEFAULT 0 NOT NULL,
	passwdexpiration integer DEFAULT 0 NOT NULL,
	passwdlastchange integer DEFAULT 0 NOT NULL,
	access smallint DEFAULT 1 NOT NULL,
	accessfrom integer DEFAULT 0 NOT NULL,
	accessto integer DEFAULT 0 NOT NULL,
	gadugadu varchar(20) DEFAULT NULL,
	modules TEXT DEFAULT NULL,
	PRIMARY KEY (id),
	UNIQUE (login)
);

/* --------------------------------------------------------
  Structure of table "customers" (customers)
-------------------------------------------------------- */
DROP SEQUENCE IF EXISTS customers_id_seq;
CREATE SEQUENCE customers_id_seq;
DROP TABLE IF EXISTS customers CASCADE;
CREATE TABLE customers (
	id integer DEFAULT nextval('customers_id_seq'::text) NOT NULL,
	lastname varchar(128)	DEFAULT '' NOT NULL,
	name varchar(128)	DEFAULT '' NOT NULL,
	status smallint 	DEFAULT 0 NOT NULL,
	type smallint		DEFAULT 0 NOT NULL,
	email varchar(255) 	DEFAULT '' NOT NULL,
	address varchar(255) 	DEFAULT '' NOT NULL,
	zip varchar(10)		DEFAULT '' NOT NULL,
	city varchar(32) 	DEFAULT '' NOT NULL,
	countryid integer	DEFAULT NULL,
	post_name varchar(255) DEFAULT NULL,
	post_address varchar(255) DEFAULT NULL,
	post_zip varchar(10)	DEFAULT NULL,
	post_city varchar(32) 	DEFAULT NULL,
	post_countryid integer	DEFAULT NULL,
	ten varchar(16) 	DEFAULT '' NOT NULL,
	ssn varchar(11) 	DEFAULT '' NOT NULL,
	regon varchar(255) 	DEFAULT '' NOT NULL,
	rbe varchar(255) 	DEFAULT '' NOT NULL, -- EDG/KRS
	icn varchar(255) 	DEFAULT '' NOT NULL, -- dow.os.
	info text		DEFAULT '' NOT NULL,
	notes text		DEFAULT '' NOT NULL,
	creationdate integer 	DEFAULT 0 NOT NULL,
	moddate integer 	DEFAULT 0 NOT NULL,
	creatorid integer 	DEFAULT 0 NOT NULL,
	modid integer 		DEFAULT 0 NOT NULL,
	deleted smallint 	DEFAULT 0 NOT NULL,
	message text		DEFAULT '' NOT NULL,
	pin varchar(6)		DEFAULT 0 NOT NULL,
	cutoffstop integer	DEFAULT 0 NOT NULL,
	consentdate integer	DEFAULT 0 NOT NULL,
	einvoice smallint 	DEFAULT NULL,
	invoicenotice smallint 	DEFAULT NULL,
	mailingnotice smallint 	DEFAULT NULL,
	divisionid integer	DEFAULT 0 NOT NULL,
	paytime smallint 	DEFAULT -1 NOT NULL,
	paytype smallint 	DEFAULT NULL,
	invoice_name VARCHAR( 255 ) DEFAULT NULL,
	invoice_address VARCHAR( 255 ) DEFAULT NULL,
	invoice_zip VARCHAR( 10 ) DEFAULT NULL,
	invoice_city VARCHAR( 32 ) DEFAULT NULL,
	invoice_countryid INTEGER DEFAULT NULL,
	invoice_ten VARCHAR( 16 ) DEFAULT NULL,
	account varchar(48) DEFAULT NULL,
	PRIMARY KEY (id)
);

CREATE INDEX customers_zip_idx ON customers (zip);
CREATE INDEX customers_lastname_idx ON customers (lastname, name);

/* -------------------------------------------------------- 
  Structure of table "numberplans" 
-------------------------------------------------------- */
DROP SEQUENCE IF EXISTS numberplans_id_seq;
CREATE SEQUENCE numberplans_id_seq;
DROP TABLE IF EXISTS numberplans CASCADE;
CREATE TABLE numberplans (
	id integer DEFAULT nextval('numberplans_id_seq'::text) NOT NULL,
	template varchar(255) DEFAULT '' NOT NULL,
	period smallint DEFAULT 0 NOT NULL,
	doctype integer DEFAULT 0 NOT NULL,
	isdefault smallint DEFAULT 0 NOT NULL,
	PRIMARY KEY (id)
);

/* ----------------------------------------------------
 Structure of table "assignments"
---------------------------------------------------*/
DROP SEQUENCE IF EXISTS assignments_id_seq;
CREATE SEQUENCE assignments_id_seq;
DROP TABLE IF EXISTS assignments CASCADE;
CREATE TABLE assignments (
	id integer default nextval('assignments_id_seq'::text) NOT NULL,
	tariffid integer 	DEFAULT 0 NOT NULL,
	liabilityid integer 	DEFAULT 0 NOT NULL,
	customerid integer	NOT NULL
	    REFERENCES customers (id) ON DELETE CASCADE ON UPDATE CASCADE,
	period smallint 	DEFAULT 0 NOT NULL,
	at integer 		DEFAULT 0 NOT NULL,
	datefrom integer	DEFAULT 0 NOT NULL,
	dateto integer		DEFAULT 0 NOT NULL,
	invoice smallint 	DEFAULT 0 NOT NULL,
	suspended smallint	DEFAULT 0 NOT NULL,
	settlement smallint	DEFAULT 0 NOT NULL,
	pdiscount numeric(4,2)	DEFAULT 0 NOT NULL,
	vdiscount numeric(9,2) DEFAULT 0 NOT NULL,
	paytype smallint    DEFAULT NULL,
	numberplanid integer DEFAULT NULL
	    REFERENCES numberplans (id) ON DELETE SET NULL ON UPDATE CASCADE,
	PRIMARY KEY (id)
);
CREATE INDEX assignments_tariffid_idx ON assignments (tariffid);
CREATE INDEX assignments_customerid_idx ON assignments (customerid);
CREATE INDEX assignments_numberplanid_idx ON assignments (numberplanid);

/* -------------------------------------------------------- 
  Structure of table "cash" 
-------------------------------------------------------- */
DROP SEQUENCE IF EXISTS cash_id_seq;
CREATE SEQUENCE cash_id_seq;
DROP TABLE IF EXISTS cash CASCADE;
CREATE TABLE cash (
	id integer 		DEFAULT nextval('cash_id_seq'::text) NOT NULL,
	time integer 		DEFAULT 0 NOT NULL,
	type smallint 		DEFAULT 0 NOT NULL,
	userid integer 		DEFAULT 0 NOT NULL,
	value numeric(9,2) 	DEFAULT 0 NOT NULL,
	taxid integer		DEFAULT 0 NOT NULL,
	customerid integer 	DEFAULT 0 NOT NULL,
	comment text 		DEFAULT '' NOT NULL,
	docid integer 		DEFAULT 0 NOT NULL,
	itemid smallint		DEFAULT 0 NOT NULL,
	importid integer	DEFAULT NULL,
	sourceid integer	DEFAULT NULL,
	PRIMARY KEY (id)
);
CREATE INDEX cash_customerid_idx ON cash (customerid);
CREATE INDEX cash_docid_idx ON cash (docid);
CREATE INDEX cash_importid_idx ON cash (importid);
CREATE INDEX cash_sourceid_idx ON cash (sourceid);
CREATE INDEX cash_time_idx ON cash (time);

/* -------------------------------------------------------- 
  Structure of table "location_states"
-------------------------------------------------------- */
DROP SEQUENCE IF EXISTS location_states_id_seq;
CREATE SEQUENCE location_states_id_seq;
DROP TABLE IF EXISTS location_states CASCADE;
CREATE TABLE location_states (
    id integer          DEFAULT nextval('location_states_id_seq'::text) NOT NULL,
    ident varchar(8)    NOT NULL, -- TERYT: WOJ
    name varchar(64)    NOT NULL, -- TERYT: NAZWA
    PRIMARY KEY (id),
    UNIQUE (name)
);

/* -------------------------------------------------------- 
  Structure of table "location_districts"
-------------------------------------------------------- */
DROP SEQUENCE IF EXISTS location_districts_id_seq;
CREATE SEQUENCE location_districts_id_seq;
DROP TABLE IF EXISTS location_districts CASCADE;
CREATE TABLE location_districts (
    id integer          DEFAULT nextval('location_districts_id_seq'::text) NOT NULL,
    name varchar(64)    NOT NULL, --TERYT: NAZWA
    ident varchar(8)    NOT NULL, --TERYT: POW
    stateid integer     NOT NULL  --TERYT: WOJ
        REFERENCES location_states (id) ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY (id),
    UNIQUE (stateid, name)
);

/* -------------------------------------------------------- 
  Structure of table "location_boroughs"
-------------------------------------------------------- */
DROP SEQUENCE IF EXISTS location_boroughs_id_seq;
CREATE SEQUENCE location_boroughs_id_seq;
DROP TABLE IF EXISTS location_boroughs CASCADE;
CREATE TABLE location_boroughs (
    id integer          DEFAULT nextval('location_boroughs_id_seq'::text) NOT NULL,
    name varchar(64)    NOT NULL, -- TERYT: NAZWA
    ident varchar(8)    NOT NULL, -- TERYT: GMI
    districtid integer  NOT NULL
        REFERENCES location_districts (id) ON DELETE CASCADE ON UPDATE CASCADE,
    type smallint       NOT NULL, -- TERYT: RODZ
    PRIMARY KEY (id),
    UNIQUE (districtid, name, type)
);

/* -------------------------------------------------------- 
  Structure of table "location_cities"
-------------------------------------------------------- */
DROP SEQUENCE IF EXISTS location_cities_id_seq;
CREATE SEQUENCE location_cities_id_seq;
DROP TABLE IF EXISTS location_cities CASCADE;
CREATE TABLE location_cities (
    id integer          DEFAULT nextval('location_cities_id_seq'::text) NOT NULL,
    ident varchar(8)    NOT NULL, -- TERYT: SYM / SYMPOD
    name varchar(64)    NOT NULL, -- TERYT: NAZWA
    cityid integer      DEFAULT NULL,
    boroughid integer   DEFAULT NULL
        REFERENCES location_boroughs (id) ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY (id)
);
CREATE INDEX location_cities_cityid ON location_cities (cityid);
CREATE INDEX location_cities_boroughid ON location_cities (boroughid, name);

/* -------------------------------------------------------- 
  Structure of table "location_street_types"
-------------------------------------------------------- */
DROP SEQUENCE IF EXISTS location_street_types_id_seq;
CREATE SEQUENCE location_street_types_id_seq;
DROP TABLE IF EXISTS location_street_types CASCADE;
CREATE TABLE location_street_types (
    id integer          DEFAULT nextval('location_street_types_id_seq'::text) NOT NULL,
    name varchar(8)     NOT NULL, -- TERYT: CECHA
    PRIMARY KEY (id)
);

/* -------------------------------------------------------- 
  Structure of table "location_streets"
-------------------------------------------------------- */
DROP SEQUENCE IF EXISTS location_streets_id_seq;
CREATE SEQUENCE location_streets_id_seq;
DROP TABLE IF EXISTS location_streets CASCADE;
CREATE TABLE location_streets (
    id integer          DEFAULT nextval('location_streets_id_seq'::text) NOT NULL,
    name varchar(128)   NOT NULL, -- TERYT: NAZWA_1
    name2 varchar(128)  DEFAULT NULL, -- TERYT: NAZWA_2
    ident varchar(8)    NOT NULL, -- TERYT: SYM_UL
    typeid integer      DEFAULT NULL
        REFERENCES location_street_types (id) ON DELETE SET NULL ON UPDATE CASCADE,
    cityid integer      NOT NULL
        REFERENCES location_cities (id) ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY (id),
    UNIQUE (cityid, name, ident)
);

/* -------------------------------------------------------- 
  Structure of table "pna"
-------------------------------------------------------- */
DROP SEQUENCE IF EXISTS pna_id_seq;
CREATE SEQUENCE pna_id_seq;
DROP TABLE IF EXISTS pna CASCADE;
CREATE TABLE pna (
	id integer DEFAULT nextval('pna_id_seq'::text) NOT NULL,
	zip varchar(128) NOT NULL,
	cityid integer NOT NULL
		REFERENCES location_cities (id) ON DELETE CASCADE ON UPDATE CASCADE,
	streetid integer DEFAULT NULL
		REFERENCES location_streets (id) ON DELETE CASCADE ON UPDATE CASCADE,
	fromhouse varchar(10) DEFAULT NULL,
	tohouse varchar(10) DEFAULT NULL,
	parity smallint DEFAULT 0 NOT NULL,
	PRIMARY KEY (id),
	UNIQUE (zip, cityid, streetid, fromhouse, tohouse, parity)
);

/* -------------------------------------------------------- 
  Structure of table "networks"
-------------------------------------------------------- */
DROP SEQUENCE IF EXISTS networks_id_seq;
CREATE SEQUENCE networks_id_seq;
DROP TABLE IF EXISTS networks CASCADE;
CREATE TABLE networks (
	id integer DEFAULT nextval('networks_id_seq'::text) NOT NULL,
	name varchar(255) 	DEFAULT '' NOT NULL,
	address bigint 		DEFAULT 0 NOT NULL,
	mask varchar(16) 	DEFAULT '' NOT NULL,
	interface varchar(16) 	DEFAULT '' NOT NULL, 
	gateway varchar(16) 	DEFAULT '' NOT NULL,
	dns varchar(16) 	DEFAULT '' NOT NULL,
	dns2 varchar(16) 	DEFAULT '' NOT NULL,
	domain varchar(64) 	DEFAULT '' NOT NULL,
	wins varchar(16) 	DEFAULT '' NOT NULL,
	dhcpstart varchar(16) 	DEFAULT '' NOT NULL,
	dhcpend varchar(16) 	DEFAULT '' NOT NULL,
	disabled smallint 	DEFAULT 0 NOT NULL,
	notes text		DEFAULT '' NOT NULL,
	PRIMARY KEY (id),
	UNIQUE (name),
	UNIQUE (address)
);

/* -------------------------------------------------------- 
  Structure of table "nodes"
-------------------------------------------------------- */
DROP SEQUENCE IF EXISTS nodes_id_seq;
CREATE SEQUENCE nodes_id_seq;
DROP TABLE IF EXISTS nodes CASCADE;
CREATE TABLE nodes (
	id integer DEFAULT nextval('nodes_id_seq'::text) NOT NULL,
	name varchar(32) 	DEFAULT '' NOT NULL,
	ipaddr bigint 		DEFAULT 0 NOT NULL,
	ipaddr_pub bigint 	DEFAULT 0 NOT NULL,
	passwd varchar(32)	DEFAULT '' NOT NULL,
	ownerid integer 	DEFAULT 0 NOT NULL,
	netdev integer 		DEFAULT 0 NOT NULL,
	linktype smallint	DEFAULT 0 NOT NULL,
	linkspeed integer	DEFAULT 100000 NOT NULL,
	port smallint		DEFAULT 0 NOT NULL,
	creationdate integer 	DEFAULT 0 NOT NULL,
	moddate integer 	DEFAULT 0 NOT NULL,
	creatorid integer 	DEFAULT 0 NOT NULL,
	modid integer 		DEFAULT 0 NOT NULL,
	access smallint 	DEFAULT 1 NOT NULL,
	warning smallint 	DEFAULT 0 NOT NULL,
	chkmac smallint 	DEFAULT 1 NOT NULL,
	halfduplex smallint	DEFAULT 0 NOT NULL,
	lastonline integer	DEFAULT 0 NOT NULL,
	info text		    DEFAULT '' NOT NULL,
	location varchar(255) DEFAULT NULL,
    location_city integer DEFAULT NULL
        REFERENCES location_cities (id) ON DELETE SET NULL ON UPDATE CASCADE,
    location_street integer DEFAULT NULL
        REFERENCES location_streets (id) ON DELETE SET NULL ON UPDATE CASCADE,
    location_house varchar(8) DEFAULT NULL,
    location_flat varchar(8) DEFAULT NULL,
	nas smallint 		DEFAULT 0 NOT NULL,
	longitude numeric(10, 6) DEFAULT NULL,
	latitude numeric(10, 6) DEFAULT NULL,
	PRIMARY KEY (id),
	UNIQUE (name),
	UNIQUE (ipaddr)
);
CREATE INDEX nodes_netdev_idx ON nodes (netdev);
CREATE INDEX nodes_ownerid_idx ON nodes (ownerid);
CREATE INDEX nodes_ipaddr_pub_idx ON nodes (ipaddr_pub);
CREATE INDEX nodes_location_street_idx ON nodes (location_street);
CREATE INDEX nodes_location_city_idx ON nodes (location_city, location_street, location_house, location_flat);

/* ----------------------------------------------------
 Structure of table "nodelocks"
---------------------------------------------------*/
DROP SEQUENCE IF EXISTS nodelocks_id_seq;
CREATE SEQUENCE nodelocks_id_seq;
DROP TABLE IF EXISTS nodelocks CASCADE;
CREATE TABLE nodelocks (
	id integer		DEFAULT nextval('nodelocks_id_seq'::text) NOT NULL,
	nodeid integer		NOT NULL
		REFERENCES nodes (id) ON DELETE CASCADE ON UPDATE CASCADE,
	days smallint		DEFAULT 0 NOT NULL,
	fromsec integer		DEFAULT 0 NOT NULL,
	tosec integer		DEFAULT 0 NOT NULL,
	PRIMARY KEY (id)
);

/* -------------------------------------------------------- 
  Structure of table "macs" 
-------------------------------------------------------- */
DROP SEQUENCE IF EXISTS macs_id_seq;
CREATE SEQUENCE macs_id_seq;
DROP TABLE IF EXISTS macs CASCADE;
CREATE TABLE macs (
	id integer		DEFAULT nextval('macs_id_seq'::text) NOT NULL,
	mac varchar(17)		DEFAULT '' NOT NULL,
	nodeid integer		NOT NULL
		REFERENCES nodes (id) ON DELETE CASCADE ON UPDATE CASCADE,
	PRIMARY KEY (id),
	CONSTRAINT macs_mac_key UNIQUE (mac, nodeid)
);

/* -------------------------------------------------------- 
  Structure of table "nodegroups" 
-------------------------------------------------------- */
DROP SEQUENCE IF EXISTS nodegroups_id_seq;
CREATE SEQUENCE nodegroups_id_seq;
DROP TABLE IF EXISTS nodegroups CASCADE;
CREATE TABLE nodegroups (
        id              integer         NOT NULL DEFAULT nextval('nodegroups_id_seq'::text),
	name            varchar(255)    NOT NULL DEFAULT '',
	prio		integer		NOT NULL DEFAULT 0,
	description     text            NOT NULL DEFAULT '',
	PRIMARY KEY (id),
	UNIQUE (name)
);

/* -------------------------------------------------------- 
  Structure of table "nodegroupassignments" 
-------------------------------------------------------- */
DROP SEQUENCE IF EXISTS nodegroupassignments_id_seq;
CREATE SEQUENCE nodegroupassignments_id_seq;
DROP TABLE IF EXISTS nodegroupassignments CASCADE;
CREATE TABLE nodegroupassignments (
        id              integer         NOT NULL DEFAULT nextval('nodegroupassignments_id_seq'::text),
	nodegroupid     integer         NOT NULL DEFAULT 0,
	nodeid          integer         NOT NULL DEFAULT 0,
	PRIMARY KEY (id),
	CONSTRAINT nodegroupassignments_nodeid_key UNIQUE (nodeid, nodegroupid)
);

/* -------------------------------------------------------- 
  Structure of table "nodeassignments" 
-------------------------------------------------------- */
DROP SEQUENCE IF EXISTS nodeassignments_id_seq;
CREATE SEQUENCE nodeassignments_id_seq;
DROP TABLE IF EXISTS nodeassignments CASCADE;
CREATE TABLE nodeassignments (
        id integer              DEFAULT nextval('nodeassignments_id_seq'::text) NOT NULL,
	nodeid integer          NOT NULL
		REFERENCES nodes (id) ON DELETE CASCADE ON UPDATE CASCADE,
	assignmentid integer    NOT NULL
		REFERENCES assignments (id) ON DELETE CASCADE ON UPDATE CASCADE,
	PRIMARY KEY (id),
	CONSTRAINT nodeassignments_nodeid_key UNIQUE (nodeid, assignmentid)
);

CREATE INDEX nodeassignments_assignmentid_idx ON nodeassignments (assignmentid);

/* --------------------------------------------------------
  Structure of table "tariffs"
-------------------------------------------------------- */
DROP SEQUENCE IF EXISTS tariffs_id_seq;
CREATE SEQUENCE tariffs_id_seq; 
DROP TABLE IF EXISTS tariffs CASCADE;
CREATE TABLE tariffs (
	id integer DEFAULT nextval('tariffs_id_seq'::text) NOT NULL,
	name varchar(255) 	DEFAULT '' NOT NULL,
	type smallint		DEFAULT 1 NOT NULL,
	value numeric(9,2) 	DEFAULT 0 NOT NULL,
	period smallint 	DEFAULT NULL,
	taxid integer 		DEFAULT 0 NOT NULL,
	prodid varchar(255) 	DEFAULT '' NOT NULL,
	uprate integer		DEFAULT 0 NOT NULL,
	upceil integer		DEFAULT 0 NOT NULL,
	downrate integer	DEFAULT 0 NOT NULL,
	downceil integer	DEFAULT 0 NOT NULL,
	climit integer		DEFAULT 0 NOT NULL,
	plimit integer		DEFAULT 0 NOT NULL,
	dlimit integer		DEFAULT 0 NOT NULL,
	uprate_n integer        DEFAULT NULL,
	upceil_n integer        DEFAULT NULL,
	downrate_n integer      DEFAULT NULL,
	downceil_n integer      DEFAULT NULL,
	climit_n integer        DEFAULT NULL,
	plimit_n integer        DEFAULT NULL,
	domain_limit integer	DEFAULT NULL,
	alias_limit integer	DEFAULT NULL,
	sh_limit integer	DEFAULT NULL,
	www_limit integer	DEFAULT NULL,
	mail_limit integer	DEFAULT NULL,
	ftp_limit integer	DEFAULT NULL,
	sql_limit integer	DEFAULT NULL,
	quota_sh_limit integer	DEFAULT NULL,
	quota_www_limit integer	DEFAULT NULL,
	quota_mail_limit integer DEFAULT NULL,
	quota_ftp_limit integer	DEFAULT NULL,
	quota_sql_limit integer	DEFAULT NULL,
	description text	DEFAULT '' NOT NULL,
	active smallint 	default '1',
	PRIMARY KEY (id),
	CONSTRAINT tariffs_name_key UNIQUE (name, value, period)
);
CREATE INDEX tariffs_type_idx ON tariffs (type);
create index tariffs_active_idx ON tariffs (active);

/* --------------------------------------------------------
  Structure of table "promotions"
-------------------------------------------------------- */
DROP SEQUENCE IF EXISTS promotions_id_seq;
CREATE SEQUENCE promotions_id_seq;
DROP TABLE IF EXISTS promotions CASCADE;
CREATE TABLE promotions (
    id integer          DEFAULT nextval('promotions_id_seq'::text) NOT NULL,
    name varchar(255)   NOT NULL,
    description text    DEFAULT NULL,
    disabled smallint   DEFAULT 0 NOT NULL,
    PRIMARY KEY (id),
    UNIQUE (name)
);

/* --------------------------------------------------------
  Structure of table "promotionschemas"
-------------------------------------------------------- */
DROP SEQUENCE IF EXISTS promotionschemas_id_seq;
CREATE SEQUENCE promotionschemas_id_seq;
DROP TABLE IF EXISTS promotionschemas CASCADE;
CREATE TABLE promotionschemas (
    id integer          DEFAULT nextval('promotionschemas_id_seq'::text) NOT NULL,
    name varchar(255)   NOT NULL,
    description text    DEFAULT NULL,
    data text           DEFAULT NULL,
    promotionid integer DEFAULT NULL
        REFERENCES promotions (id) ON DELETE CASCADE ON UPDATE CASCADE,
    disabled smallint   DEFAULT 0 NOT NULL,
    continuation smallint   DEFAULT NULL,
    ctariffid integer DEFAULT NULL
        REFERENCES tariffs (id) ON DELETE RESTRICT ON UPDATE CASCADE,
    PRIMARY KEY (id),
    CONSTRAINT promotionschemas_promotionid_key UNIQUE (promotionid, name)
);
CREATE INDEX promotionschemas_ctariffid_idx ON promotionschemas (ctariffid);

/* --------------------------------------------------------
  Structure of table "promotionassignments"
-------------------------------------------------------- */
DROP SEQUENCE IF EXISTS promotionassignments_id_seq;
CREATE SEQUENCE promotionassignments_id_seq;
DROP TABLE IF EXISTS promotionassignments CASCADE;
CREATE TABLE promotionassignments (
    id integer          DEFAULT nextval('promotionassignments_id_seq'::text) NOT NULL,
    promotionschemaid integer DEFAULT NULL
        REFERENCES promotionschemas (id) ON DELETE CASCADE ON UPDATE CASCADE,
    tariffid integer    DEFAULT NULL
        REFERENCES tariffs (id) ON DELETE CASCADE ON UPDATE CASCADE,
    data text           DEFAULT NULL,
    PRIMARY KEY (id),
    CONSTRAINT promotionassignments_promotionschemaid_key UNIQUE (promotionschemaid, tariffid)
);
CREATE INDEX promotionassignments_tariffid_idx ON promotionassignments (tariffid);

/* --------------------------------------------------------
  Structure of table "liabilities"
-------------------------------------------------------- */
DROP SEQUENCE IF EXISTS liabilities_id_seq;
CREATE SEQUENCE liabilities_id_seq;
DROP TABLE IF EXISTS liabilities CASCADE;
CREATE TABLE liabilities (
	id integer DEFAULT nextval('liabilities_id_seq'::text) NOT NULL,
	value numeric(9,2)  	DEFAULT 0 NOT NULL,
	name text           	DEFAULT '' NOT NULL,
	taxid integer       	DEFAULT 0 NOT NULL,
	prodid varchar(255) 	DEFAULT '' NOT NULL,
	PRIMARY KEY (id)
);

/* ---------------------------------------------------------
  Structure of table "payments"
--------------------------------------------------------- */
DROP SEQUENCE IF EXISTS payments_id_seq;
CREATE SEQUENCE payments_id_seq;
DROP TABLE IF EXISTS payments CASCADE;
CREATE TABLE payments (
	id integer DEFAULT nextval('payments_id_seq'::text) NOT NULL,
	name varchar(255) 	DEFAULT '' NOT NULL,
	value numeric(9,2) 	DEFAULT 0 NOT NULL,
	creditor varchar(255) 	DEFAULT '' NOT NULL,
	period smallint		DEFAULT 0 NOT NULL,
	at smallint 		DEFAULT 0 NOT NULL,
	description text	DEFAULT '' NOT NULL,
	PRIMARY KEY (id)
);

/* -------------------------------------------------------- 
  Structure of table "taxes" 
-------------------------------------------------------- */
DROP SEQUENCE IF EXISTS taxes_id_seq;
CREATE SEQUENCE taxes_id_seq;
DROP TABLE IF EXISTS taxes CASCADE;
CREATE TABLE taxes (
    id integer DEFAULT nextval('taxes_id_seq'::text) NOT NULL,
    value numeric(4,2) DEFAULT 0 NOT NULL,
    taxed smallint DEFAULT 0 NOT NULL,
    label varchar(16) DEFAULT '' NOT NULL,
    validfrom integer DEFAULT 0 NOT NULL,
    validto integer DEFAULT 0 NOT NULL,
    PRIMARY KEY (id)
);

/* -------------------------------------------------------- 
  Structure of table "documents" 
-------------------------------------------------------- */
DROP SEQUENCE IF EXISTS documents_id_seq;
CREATE SEQUENCE documents_id_seq;
DROP TABLE IF EXISTS documents CASCADE;
CREATE TABLE documents (
	id integer DEFAULT nextval('documents_id_seq'::text) NOT NULL,
	type smallint		DEFAULT 0 NOT NULL,
	number integer		DEFAULT 0 NOT NULL,
	numberplanid integer	DEFAULT 0 NOT NULL,
	extnumber varchar(255)	DEFAULT '' NOT NULL,
	cdate integer		DEFAULT 0 NOT NULL,
	sdate integer		DEFAULT 0 NOT NULL,
	customerid integer	DEFAULT 0 NOT NULL,
	userid integer		DEFAULT 0 NOT NULL,
	divisionid integer	DEFAULT 0 NOT NULL,
	name varchar(255)	DEFAULT '' NOT NULL,
	address varchar(255)	DEFAULT '' NOT NULL,
	zip varchar(10)		DEFAULT '' NOT NULL,
	city varchar(32)	DEFAULT '' NOT NULL,
	countryid integer	DEFAULT 0 NOT NULL,
	ten varchar(16)		DEFAULT '' NOT NULL,
	ssn varchar(11)		DEFAULT '' NOT NULL,
	paytime smallint	DEFAULT 0 NOT NULL,
	paytype smallint	DEFAULT NULL,
	closed smallint		DEFAULT 0 NOT NULL,
	reference integer	DEFAULT 0 NOT NULL,
	reason varchar(255)	DEFAULT '' NOT NULL,
	sale smallint DEFAULT '1' NOT NULL,
	PRIMARY KEY (id)
);
CREATE INDEX documents_cdate_idx ON documents(cdate);
CREATE INDEX documents_numberplanid_idx ON documents(numberplanid);
CREATE INDEX documents_customerid_idx ON documents(customerid);
CREATE INDEX documents_closed_idx ON documents(closed);
CREATE INDEX documents_sale_idx ON documents (sale);

/* -------------------------------------------------------- 
  Structure of table "documentcontents" 
-------------------------------------------------------- */
DROP TABLE IF EXISTS documentcontents CASCADE;
CREATE TABLE documentcontents (
	docid integer 		DEFAULT 0 NOT NULL,
	title text 		DEFAULT '' NOT NULL,
	fromdate integer 	DEFAULT 0 NOT NULL,
	todate integer 		DEFAULT 0 NOT NULL,
	filename varchar(255) 	DEFAULT '' NOT NULL,
	contenttype varchar(255) DEFAULT '' NOT NULL,
	md5sum varchar(32) 	DEFAULT '' NOT NULL,
	description text 	DEFAULT '' NOT NULL,
	UNIQUE (docid)
);
CREATE INDEX documentcontents_md5sum_idx ON documentcontents (md5sum);
CREATE INDEX documentcontents_todate_idx ON documentcontents (todate);
CREATE INDEX documentcontents_fromdate_idx ON documentcontents (fromdate);


/* -------------------------------------------------------- 
  Structure of table "receiptcontents" 
-------------------------------------------------------- */
DROP TABLE IF EXISTS receiptcontents CASCADE;
CREATE TABLE receiptcontents (
	docid integer		DEFAULT 0 NOT NULL,
	itemid smallint		DEFAULT 0 NOT NULL,
	value numeric(9,2)	DEFAULT 0 NOT NULL,
	regid integer		DEFAULT 0 NOT NULL,
	description text 	DEFAULT '' NOT NULL
);
CREATE INDEX receiptcontents_docid_idx ON receiptcontents(docid);
CREATE INDEX receiptcontents_regid_idx ON receiptcontents(regid);

/* -------------------------------------------------------- 
  Structure of table "invoicecontents" 
-------------------------------------------------------- */
DROP TABLE IF EXISTS invoicecontents CASCADE;
CREATE TABLE invoicecontents (
	docid integer 		DEFAULT 0 NOT NULL,
	itemid smallint		DEFAULT 0 NOT NULL,
	value numeric(9,2) 	DEFAULT 0 NOT NULL,
	taxid integer 		DEFAULT 0 NOT NULL,
	prodid varchar(255) 	DEFAULT '' NOT NULL,
	content varchar(16) 	DEFAULT '' NOT NULL,
	count numeric(9,2) 	DEFAULT 0 NOT NULL,
	description text 	DEFAULT '' NOT NULL,
	tariffid integer 	DEFAULT 0 NOT NULL,
	pdiscount numeric(4,2) DEFAULT 0 NOT NULL,
	vdiscount numeric(9,2) DEFAULT 0 NOT NULL
);
CREATE INDEX invoicecontents_docid_idx ON invoicecontents (docid);

/* -------------------------------------------------------- 
  Structure of table "debitnotecontents" 
-------------------------------------------------------- */
DROP TABLE IF EXISTS debitnotecontents CASCADE;
DROP SEQUENCE IF EXISTS debitnotecontents_id_seq;
CREATE SEQUENCE debitnotecontents_id_seq;
CREATE TABLE debitnotecontents (
	id integer 		DEFAULT nextval('debitnotecontents_id_seq'::text) NOT NULL,
        docid integer           DEFAULT 0 NOT NULL,
	itemid smallint         DEFAULT 0 NOT NULL,
	value numeric(9,2)      DEFAULT 0 NOT NULL,
        description text 	DEFAULT '' NOT NULL,
	PRIMARY KEY (id),
	CONSTRAINT debitnotecontents_docid_key UNIQUE (docid, itemid)
);
/* -------------------------------------------------------- 
  Structure of table "numberplanassignments" 
-------------------------------------------------------- */
DROP SEQUENCE IF EXISTS numberplanassignments_id_seq;
CREATE SEQUENCE numberplanassignments_id_seq;
DROP TABLE IF EXISTS numberplanassignments CASCADE;
CREATE TABLE numberplanassignments (
	id integer DEFAULT nextval('numberplanassignments_id_seq'::text) NOT NULL,
	planid integer DEFAULT 0 NOT NULL,
	divisionid integer DEFAULT 0 NOT NULL,
	PRIMARY KEY (id),
	CONSTRAINT numberplanassignments_planid_key UNIQUE (planid, divisionid)
);
CREATE INDEX numberplanassignments_divisionid_idx ON numberplanassignments (divisionid);

/* -------------------------------------------------------- 
  Structure of table "customergroups" 
-------------------------------------------------------- */
DROP SEQUENCE IF EXISTS customergroups_id_seq;
CREATE SEQUENCE customergroups_id_seq;
DROP TABLE IF EXISTS customergroups CASCADE;
CREATE TABLE customergroups (
	id integer DEFAULT nextval('customergroups_id_seq'::text) NOT NULL, 
	name varchar(255) DEFAULT '' NOT NULL, 
	description text DEFAULT '' NOT NULL, 
	PRIMARY KEY (id), 
	UNIQUE (name)
);

/* -------------------------------------------------------- 
  Structure of table "customerassignments" 
-------------------------------------------------------- */
DROP SEQUENCE IF EXISTS customerassignments_id_seq;
CREATE SEQUENCE customerassignments_id_seq;
DROP TABLE IF EXISTS customerassignments CASCADE;
CREATE TABLE customerassignments (
	id integer DEFAULT nextval('customerassignments_id_seq'::text) NOT NULL,
	customergroupid integer NOT NULL
	    REFERENCES customergroups (id) ON DELETE CASCADE ON UPDATE CASCADE,
	customerid integer NOT NULL
	    REFERENCES customers (id) ON DELETE CASCADE ON UPDATE CASCADE,
	PRIMARY KEY (id),
	CONSTRAINT customerassignments_customergroupid_key UNIQUE (customergroupid, customerid)
);

CREATE INDEX customerassignments_customerid_idx ON customerassignments (customerid);


DROP SEQUENCE IF EXISTS contractorassignments_id_seq;
DROP TABLE IF EXISTS contractorassignments CASCADE;
DROP SEQUENCE IF EXISTS contractorgroups_id_seq;
DROP TABLE IF EXISTS contractorgroups CASCADE;

CREATE SEQUENCE contractorgroups_id_seq;
CREATE TABLE contractorgroups (
	id integer DEFAULT nextval('contractorgroups_id_seq'::text) NOT NULL, 
	name varchar(255) DEFAULT '' NOT NULL, 
	description text DEFAULT '' NOT NULL, 
	PRIMARY KEY (id), 
	UNIQUE (name)
);

CREATE SEQUENCE contractorassignments_id_seq;
CREATE TABLE contractorassignments (
	id integer DEFAULT nextval('contractorassignments_id_seq'::text) NOT NULL,
	contractorgroupid integer NOT NULL REFERENCES contractorgroups (id) ON DELETE CASCADE ON UPDATE CASCADE,
	customerid integer NOT NULL REFERENCES customers (id) ON DELETE CASCADE ON UPDATE CASCADE,
	PRIMARY KEY (id),
	CONSTRAINT contractorassignments_contractorgroupid_key UNIQUE (contractorgroupid, customerid)
);
CREATE INDEX contractorassignments_customerid_idx ON contractorassignments (customerid);



/* -------------------------------------------------------- 
  Structure of table "stats" 
-------------------------------------------------------- */
DROP TABLE IF EXISTS stats CASCADE;
CREATE TABLE stats (
	nodeid integer 		DEFAULT 0 NOT NULL,
	dt integer 		DEFAULT 0 NOT NULL,
	upload bigint 		DEFAULT 0,
	download bigint 	DEFAULT 0,
	nodesessionid integer	DEFAULT 0 NOT NULL,
	PRIMARY KEY (nodeid, dt)
);
CREATE INDEX stats_dt_idx ON stats(dt);
CREATE INDEX stats_nodesessionid_idx ON stats(nodesessionid);

/* -------------------------------------------------------- 
  Structure of table "nodesessions" 
-------------------------------------------------------- */
CREATE SEQUENCE nodesessions_id_seq;
CREATE TABLE nodesessions (
	id integer		DEFAULT nextval('nodesessions_id_seq'::text) NOT NULL,
	customerid integer	DEFAULT 0 NOT NULL,
	nodeid integer		DEFAULT 0 NOT NULL,
	ipaddr bigint		DEFAULT 0 NOT NULL,
	mac varchar(17)		DEFAULT '' NOT NULL,
	start integer		DEFAULT 0 NOT NULL,
	stop integer		DEFAULT 0 NOT NULL,
	download bigint		DEFAULT 0,
	upload bigint		DEFAULT 0,
	tag varchar(32)		DEFAULT '' NOT NULL,
	PRIMARY KEY (id)
);
CREATE INDEX nodesessions_customerid_idx ON nodesessions(customerid);
CREATE INDEX nodesessions_nodeid_idx ON nodesessions(nodeid);
CREATE INDEX nodesessions_tag_idx ON nodesessions(tag);

/* ---------------------------------------------------
 Structure of table "netlinks"
----------------------------------------------------*/
DROP SEQUENCE IF EXISTS netlinks_id_seq;
CREATE SEQUENCE netlinks_id_seq;
DROP TABLE IF EXISTS netlinks CASCADE;
CREATE TABLE netlinks (
	id integer default nextval('netlinks_id_seq'::text) NOT NULL,
	src integer 		DEFAULT 0 NOT NULL,
	dst integer 		DEFAULT 0 NOT NULL,
	type smallint		DEFAULT 0 NOT NULL,
	speed integer		DEFAULT 100000 NOT NULL,
	srcport smallint	DEFAULT 0 NOT NULL,
	dstport smallint	DEFAULT 0 NOT NULL,
	PRIMARY KEY  (id),
	CONSTRAINT netlinks_src_key UNIQUE (src, dst)
);

/* ---------------------------------------------------
 Functions for network address translations
------------------------------------------------------*/
CREATE OR REPLACE FUNCTION inet_ntoa(bigint) RETURNS text AS $$
SELECT
        ($1/(256*256*256))::text
        ||'.'||
	($1/(256*256) - $1/(256*256*256)*256)::text
	||'.'||
	($1/256 - $1/(256*256)*256)::text
	||'.'||
	($1 - $1/256*256)::text;
$$ LANGUAGE SQL IMMUTABLE;

CREATE OR REPLACE FUNCTION inet_aton(text) RETURNS bigint AS $$
SELECT
	split_part($1,'.',1)::int8*(256*256*256)+
	split_part($1,'.',2)::int8*(256*256)+
	split_part($1,'.',3)::int8*256+
	split_part($1,'.',4)::int8;
$$ LANGUAGE SQL IMMUTABLE;

CREATE OR REPLACE FUNCTION mask2prefix(bigint) RETURNS smallint AS $$
SELECT
	length(replace(ltrim(textin(bit_out($1::bit(32))), '0'), '0', ''))::smallint;
$$ LANGUAGE SQL IMMUTABLE;

CREATE OR REPLACE FUNCTION broadcast(bigint, bigint) RETURNS bigint AS $$
SELECT
	($1::bit(32) |  ~($2::bit(32)))::bigint;
$$ LANGUAGE SQL IMMUTABLE;

/* --------------------------------------------------
 Tables for RT (Helpdesk)
-----------------------------------------------------*/

DROP SEQUENCE IF EXISTS rtqueues_id_seq;
CREATE SEQUENCE rtqueues_id_seq;
DROP TABLE IF EXISTS rtqueues CASCADE;
CREATE TABLE rtqueues (
  id integer default nextval('rtqueues_id_seq'::text) NOT NULL,
  name varchar(255) 	DEFAULT '' NOT NULL,
  email varchar(255) 	DEFAULT '' NOT NULL,
  description text	DEFAULT '' NOT NULL,
  PRIMARY KEY (id),
  UNIQUE (name)
);

DROP SEQUENCE IF EXISTS rttickets_id_seq;
CREATE SEQUENCE rttickets_id_seq;
DROP TABLE IF EXISTS rttickets CASCADE;
CREATE TABLE rttickets (
  id integer default nextval('rttickets_id_seq'::text) NOT NULL,
  queueid integer 	NOT NULL
    REFERENCES rtqueues (id) ON DELETE CASCADE ON UPDATE CASCADE,
  requestor varchar(255) DEFAULT '' NOT NULL,
  subject varchar(255) 	DEFAULT '' NOT NULL,
  state smallint 	DEFAULT 0 NOT NULL,
  cause smallint	DEFAULT 0 NOT NULL,
  owner integer 	DEFAULT 0 NOT NULL,
  customerid integer 	DEFAULT 0 NOT NULL,
  creatorid integer 	DEFAULT 0 NOT NULL,
  createtime integer 	DEFAULT 0 NOT NULL,
  resolvetime integer 	DEFAULT 0 NOT NULL,
  PRIMARY KEY (id)
);

CREATE INDEX rttickets_queueid_idx ON rttickets (queueid);
CREATE INDEX rttickets_customerid_idx ON rttickets (customerid);
CREATE INDEX rttickets_creatorid_idx ON rttickets (creatorid);
CREATE INDEX rttickets_createtime_idx ON rttickets (createtime);

DROP SEQUENCE IF EXISTS rtmessages_id_seq;
CREATE SEQUENCE rtmessages_id_seq;
DROP TABLE IF EXISTS rtmessages CASCADE;
CREATE TABLE rtmessages (
  id integer default nextval('rtmessages_id_seq'::text) NOT NULL,
  ticketid integer 	NOT NULL
    REFERENCES rttickets (id) ON DELETE CASCADE ON UPDATE CASCADE,
  userid integer 	DEFAULT 0 NOT NULL,
  customerid integer 	DEFAULT 0 NOT NULL,
  mailfrom varchar(255) DEFAULT '' NOT NULL,
  subject varchar(255) 	DEFAULT '' NOT NULL,
  messageid varchar(255) DEFAULT '' NOT NULL,
  inreplyto integer 	DEFAULT 0 NOT NULL,
  replyto text 		DEFAULT '' NOT NULL,
  headers text 		DEFAULT '' NOT NULL,
  body text		DEFAULT '' NOT NULL,
  createtime integer	DEFAULT 0 NOT NULL,
  PRIMARY KEY (id)
);

CREATE INDEX rtmessages_ticketid_idx ON rtmessages (ticketid);

DROP SEQUENCE IF EXISTS rtnotes_id_seq;
CREATE SEQUENCE rtnotes_id_seq;
DROP TABLE IF EXISTS rtnotes CASCADE;
CREATE TABLE rtnotes (
	id integer default nextval('rtnotes_id_seq'::text) NOT NULL,
	ticketid integer      NOT NULL
	    REFERENCES rttickets (id) ON DELETE CASCADE ON UPDATE CASCADE,
    userid integer        NOT NULL
        REFERENCES users (id) ON DELETE CASCADE ON UPDATE CASCADE,
	body text             DEFAULT '' NOT NULL,
	createtime integer    DEFAULT 0 NOT NULL,
	PRIMARY KEY (id)
);

CREATE INDEX rtnotes_ticketid_idx ON rtnotes (ticketid);
CREATE INDEX rtnotes_userid_idx ON rtnotes (userid);

DROP SEQUENCE IF EXISTS rtrights_id_seq;
CREATE SEQUENCE rtrights_id_seq;
DROP TABLE IF EXISTS rtrights CASCADE;
CREATE TABLE rtrights (
    id integer DEFAULT nextval('rtrights_id_seq'::text) NOT NULL, 
    userid integer NOT NULL
        REFERENCES users (id) ON DELETE CASCADE ON UPDATE CASCADE,
    queueid integer NOT NULL
        REFERENCES rtqueues (id) ON DELETE CASCADE ON UPDATE CASCADE,
    rights integer DEFAULT 0 NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT rtrights_userid_key UNIQUE (userid, queueid)
);

DROP TABLE IF EXISTS rtattachments CASCADE;
CREATE TABLE rtattachments (
	messageid integer 	    NOT NULL
	    REFERENCES rtmessages (id) ON DELETE CASCADE ON UPDATE CASCADE,
	filename varchar(255) 	DEFAULT '' NOT NULL,
	contenttype varchar(255) DEFAULT '' NOT NULL
);

CREATE INDEX rtattachments_message_idx ON rtattachments (messageid);

DROP SEQUENCE IF EXISTS rtcategories_id_seq;
CREATE SEQUENCE rtcategories_id_seq;
DROP TABLE IF EXISTS rtcategories CASCADE;
CREATE TABLE rtcategories (
	id integer		DEFAULT nextval('rtcategories_id_seq'::text) NOT NULL,
	name varchar(255)	DEFAULT '' NOT NULL,
	description text	DEFAULT '' NOT NULL,
	PRIMARY KEY (id),
	UNIQUE (name)
);

DROP SEQUENCE IF EXISTS rtcategoryusers_id_seq;
CREATE SEQUENCE rtcategoryusers_id_seq;
DROP TABLE IF EXISTS rtcategoryusers CASCADE;
CREATE TABLE rtcategoryusers (
	id integer		DEFAULT nextval('rtcategoryusers_id_seq'::text) NOT NULL,
	userid integer		NOT NULL
		REFERENCES users (id) ON DELETE CASCADE ON UPDATE CASCADE,
	categoryid integer	NOT NULL
		REFERENCES rtcategories (id) ON DELETE CASCADE ON UPDATE CASCADE,
	PRIMARY KEY (id),
	CONSTRAINT rtcategories_userid_key UNIQUE (userid, categoryid)
);

DROP SEQUENCE IF EXISTS rtticketcategories_id_seq;
CREATE SEQUENCE rtticketcategories_id_seq;
DROP TABLE IF EXISTS rtticketcategories CASCADE;
CREATE TABLE rtticketcategories (
	id integer		DEFAUlT nextval('rtticketcategories_id_seq'::text) NOT NULL,
	ticketid integer	NOT NULL
		REFERENCES rttickets (id) ON DELETE CASCADE ON UPDATE CASCADE,
	categoryid integer	NOT NULL
		REFERENCES rtcategories (id) ON DELETE CASCADE ON UPDATE CASCADE,
	PRIMARY KEY (id),
	CONSTRAINT rtticketcategories_ticketid_key UNIQUE (ticketid, categoryid)
);

/* ---------------------------------------------------
 Structure of table "passwd" (accounts)
------------------------------------------------------*/
DROP SEQUENCE IF EXISTS passwd_id_seq;
CREATE SEQUENCE passwd_id_seq;
DROP TABLE IF EXISTS passwd CASCADE;
CREATE TABLE passwd (
        id integer DEFAULT nextval('passwd_id_seq'::text) NOT NULL,
	ownerid integer 	DEFAULT 0 NOT NULL,
	login varchar(200) 	DEFAULT '' NOT NULL,
	password varchar(200) 	DEFAULT '' NOT NULL,
	lastlogin integer 	DEFAULT 0 NOT NULL,
	uid integer 		DEFAULT 0 NOT NULL,
	home varchar(255) 	DEFAULT '' NOT NULL,
	type smallint 		DEFAULT 0 NOT NULL,
	expdate	integer		DEFAULT 0 NOT NULL,
	domainid integer	DEFAULT 0 NOT NULL,
	realname varchar(255)	DEFAULT '' NOT NULL,
	createtime integer	DEFAULT 0 NOT NULL,
	quota_sh integer	DEFAULT 0 NOT NULL,
	quota_mail integer	DEFAULT 0 NOT NULL,
	quota_www integer	DEFAULT 0 NOT NULL,
	quota_ftp integer	DEFAULT 0 NOT NULL,
	quota_sql integer	DEFAULT 0 NOT NULL,
	mail_forward varchar(255) DEFAULT '' NOT NULL,
	mail_bcc varchar(255) 	DEFAULT '' NOT NULL,
	description text	DEFAULT '' NOT NULL,
	PRIMARY KEY (id),
	CONSTRAINT passwd_login_key UNIQUE (login, domainid)
);
CREATE INDEX passwd_ownerid_idx ON passwd (ownerid);

/* ---------------------------------------------------
 Structure of table "domains"
------------------------------------------------------*/
DROP SEQUENCE IF EXISTS domains_id_seq;
CREATE SEQUENCE domains_id_seq;
DROP TABLE IF EXISTS domains CASCADE;
CREATE TABLE domains (
	id integer DEFAULT nextval('domains_id_seq'::text) NOT NULL,
	ownerid integer 	DEFAULT 0 NOT NULL,
	name varchar(255) 	DEFAULT '' NOT NULL,
	description text 	DEFAULT '' NOT NULL,
	master varchar(128) 	DEFAULT NULL,
	last_check integer 	DEFAULT NULL,
	type varchar(6) 	DEFAULT '' NOT NULL,
	notified_serial integer DEFAULT NULL,
	account varchar(40) 	DEFAULT NULL,
	mxbackup smallint	DEFAULT 0 NOT NULL,
	PRIMARY KEY (id),
	UNIQUE (name)
);
CREATE INDEX domains_ownerid_idx ON domains (ownerid);

/* ---------------------------------------------------
 Structure of table "records" (DNS)
------------------------------------------------------*/
DROP SEQUENCE IF EXISTS records_id_seq;
CREATE SEQUENCE records_id_seq;
DROP TABLE IF EXISTS records CASCADE;
CREATE TABLE records (
	id integer		DEFAULT nextval('records_id_seq'::text) NOT NULL,
	domain_id integer	DEFAULT NULL
		REFERENCES domains (id) ON DELETE CASCADE ON UPDATE CASCADE,
	name varchar(255)	DEFAULT NULL,
	type varchar(6)		DEFAULT NULL,
	content varchar(255)	DEFAULT NULL,
	ttl integer		DEFAULT NULL,
	prio integer		DEFAULT NULL,
	change_date integer	DEFAULT NULL,
	PRIMARY KEY (id)
);

CREATE INDEX records_name_type_idx ON records (name, type, domain_id);
CREATE INDEX records_domain_id_idx ON records (domain_id);

/* ---------------------------------------------------
 Structure of table "supermasters" (DNS)
------------------------------------------------------*/
DROP SEQUENCE IF EXISTS supermasters_id_seq;
CREATE SEQUENCE supermasters_id_seq;
DROP TABLE IF EXISTS supermasters CASCADE;
CREATE TABLE supermasters (
	id integer		DEFAULT nextval('supermasters_id_seq'::text) NOT NULL,
	ip varchar(25)		NOT NULL,
	nameserver varchar(255) NOT NULL,
	account varchar(40)	DEFAULT NULL,
	PRIMARY KEY (id)
);

/* ---------------------------------------------------
 Structure of table "aliases"
------------------------------------------------------*/
DROP SEQUENCE IF EXISTS aliases_id_seq;
CREATE SEQUENCE aliases_id_seq;
DROP TABLE IF EXISTS aliases CASCADE;
CREATE TABLE aliases (
	id 		integer 	DEFAULT nextval('aliases_id_seq'::text) NOT NULL,
	login 		varchar(255) 	DEFAULT '' NOT NULL,
	domainid 	integer 	DEFAULT 0 NOT NULL,
	PRIMARY KEY (id),
	CONSTRAINT aliases_login_key UNIQUE (login, domainid)
);

/* ---------------------------------------------------
 Structure of table "aliasassignments"
------------------------------------------------------*/
DROP SEQUENCE IF EXISTS aliasassignments_id_seq;
CREATE SEQUENCE aliasassignments_id_seq;
DROP TABLE IF EXISTS aliasassignments CASCADE;
CREATE TABLE aliasassignments (
	id              integer         DEFAULT nextval('passwd_id_seq'::text) NOT NULL,
	aliasid         integer         DEFAULT 0 NOT NULL,
	accountid       integer         DEFAULT 0 NOT NULL,
	mail_forward    varchar(255)    DEFAULT '' NOT NULL,
	PRIMARY KEY (id),
	CONSTRAINT aliasassignments_aliasid_key UNIQUE (aliasid, accountid, mail_forward)
);

/* ---------------------------------------------------
 LMS-UI Configuration table
------------------------------------------------------*/
DROP SEQUENCE IF EXISTS uiconfig_id_seq;
CREATE SEQUENCE uiconfig_id_seq;
DROP TABLE IF EXISTS uiconfig CASCADE;
CREATE TABLE uiconfig (
    id 		integer 	DEFAULT nextval('uiconfig_id_seq'::text) NOT NULL,
    section 	varchar(64) 	NOT NULL DEFAULT '',
    var 	varchar(64) 	NOT NULL DEFAULT '',
    value 	text 		NOT NULL DEFAULT '',
    description text 		NOT NULL DEFAULT '',
    disabled 	smallint 	NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    CONSTRAINT uiconfig_section_key UNIQUE (section, var)
);

/* ---------------------------------------------------
 Structure of table "events" (Timetable)
------------------------------------------------------*/
DROP SEQUENCE IF EXISTS events_id_seq;
CREATE SEQUENCE events_id_seq;
DROP TABLE IF EXISTS events CASCADE;
CREATE TABLE events (
	id 		integer 	DEFAULT nextval('events_id_seq'::text) NOT NULL,
	title 		varchar(255) 	DEFAULT '' NOT NULL,
	description 	text 		DEFAULT '' NOT NULL,
	note 		text 		DEFAULT '' NOT NULL,
	date 		integer 	DEFAULT 0 NOT NULL,
	begintime 	smallint 	DEFAULT 0 NOT NULL,
	endtime 	smallint 	DEFAULT 0 NOT NULL,
	userid 		integer 	DEFAULT 0 NOT NULL,
	customerid 	integer 	DEFAULT 0 NOT NULL,
	private 	smallint 	DEFAULT 0 NOT NULL,
	closed 		smallint 	DEFAULT 0 NOT NULL,
	moddate		integer		DEFAULT 0 NOT NULL,
	moduserid	integer		DEFAULT 0 NOT NULL,
	PRIMARY KEY (id)
);
CREATE INDEX events_date_idx ON events(date);

/* ---------------------------------------------------
 Structure of table "events" (Timetable)
------------------------------------------------------*/
DROP TABLE IF EXISTS eventassignments CASCADE;
CREATE TABLE eventassignments (
	eventid 	integer 	DEFAULT 0 NOT NULL,
	userid 		integer 	DEFAULT 0 NOT NULL,
	CONSTRAINT eventassignments_eventid_key UNIQUE (eventid, userid)
);

/* ---------------------------------------------------
 Structure of table "sessions"
------------------------------------------------------*/
DROP TABLE IF EXISTS sessions CASCADE;
CREATE TABLE sessions (
    id 		varchar(50) 	NOT NULL DEFAULT '', 
    ctime 	integer 	NOT NULL DEFAULT 0, 
    mtime 	integer 	NOT NULL DEFAULT 0, 
    atime 	integer 	NOT NULL DEFAULT 0, 
    vdata 	text 		NOT NULL, 
    content 	text 		NOT NULL, 
    PRIMARY KEY (id)
);

/* ---------------------------------------------------
 Structure of table "cashsources"
------------------------------------------------------*/
DROP SEQUENCE IF EXISTS cashsources_id_seq;
CREATE SEQUENCE cashsources_id_seq;
DROP TABLE IF EXISTS cashsources CASCADE;
CREATE TABLE cashsources (
    id integer      	DEFAULT nextval('cashsources_id_seq'::text) NOT NULL,
    name varchar(32)    DEFAULT '' NOT NULL,
    description text	DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE (name)
);

/* ---------------------------------------------------
 Structure of table "sourcefiles"
------------------------------------------------------*/
DROP SEQUENCE IF EXISTS sourcefiles_id_seq;
CREATE SEQUENCE sourcefiles_id_seq;
DROP TABLE IF EXISTS sourcefiles CASCADE;
CREATE TABLE sourcefiles (
    id integer      	DEFAULT nextval('sourcefiles_id_seq'::text) NOT NULL,
    userid integer     DEFAULT NULL
        REFERENCES users (id) ON DELETE SET NULL ON UPDATE CASCADE,
    name varchar(255)   NOT NULL,
    idate integer	    NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT sourcefiles_idate_key UNIQUE (idate, name)
);

CREATE INDEX sourcefiles_userid_idx ON sourcefiles (userid);

/* ---------------------------------------------------
 Structure of table "cashimport"
------------------------------------------------------*/
DROP SEQUENCE IF EXISTS cashimport_id_seq;
CREATE SEQUENCE cashimport_id_seq;
DROP TABLE IF EXISTS cashimport CASCADE;
CREATE TABLE cashimport (
    id integer 			DEFAULT nextval('cashimport_id_seq'::text) NOT NULL,
    date integer 		DEFAULT 0 NOT NULL,
    value numeric(9,2) 		DEFAULT 0 NOT NULL,
    customer varchar(150) 	DEFAULT '' NOT NULL,
    description varchar(150) 	DEFAULT '' NOT NULL,
    customerid integer 		DEFAULT NULL
	    REFERENCES customers (id) ON DELETE SET NULL ON UPDATE CASCADE,
    hash varchar(50) 		DEFAULT '' NOT NULL,
    closed smallint 		DEFAULT 0 NOT NULL,
    sourceid integer		DEFAULT NULL
	    REFERENCES cashsources (id) ON DELETE SET NULL ON UPDATE CASCADE,
    sourcefileid integer    DEFAULT NULL
	    REFERENCES sourcefiles (id) ON DELETE SET NULL ON UPDATE CASCADE,
    PRIMARY KEY (id)
);

CREATE INDEX cashimport_hash_idx ON cashimport (hash);
CREATE INDEX cashimport_customerid_idx ON cashimport (customerid);
CREATE INDEX cashimport_sourcefileid_idx ON cashimport (sourcefileid);
CREATE INDEX cashimport_sourceid_idx ON cashimport (sourceid);

/* ---------------------------------------------------
 Structure of table "hosts"
------------------------------------------------------*/
DROP SEQUENCE IF EXISTS hosts_id_seq;
CREATE SEQUENCE hosts_id_seq;
DROP TABLE IF EXISTS hosts CASCADE;
CREATE TABLE hosts (
    id integer DEFAULT nextval('hosts_id_seq'::text) NOT NULL,
    name varchar(255) 		DEFAULT '' NOT NULL,
    description text 		DEFAULT '' NOT NULL,
    lastreload integer 		DEFAULT 0 NOT NULL,
    reload smallint 		DEFAULT 0 NOT NULL,
    PRIMARY KEY (id),
    UNIQUE (name)
);

/* ---------------------------------------------------
 Structure of table "daemoninstances" (lmsd config)
------------------------------------------------------*/
DROP SEQUENCE IF EXISTS daemoninstances_id_seq;
CREATE SEQUENCE daemoninstances_id_seq;
DROP TABLE IF EXISTS daemoninstances CASCADE;
CREATE TABLE daemoninstances (
    id integer DEFAULT nextval('daemoninstances_id_seq'::text) NOT NULL,
    name varchar(255) 		DEFAULT '' NOT NULL,
    hostid integer 		DEFAULT 0 NOT NULL,
    module varchar(255) 	DEFAULT '' NOT NULL,
    crontab varchar(255) 	DEFAULT '' NOT NULL,
    priority integer 		DEFAULT 0 NOT NULL,
    description text 		DEFAULT '' NOT NULL,
    disabled smallint 		DEFAULT 0 NOT NULL,
    PRIMARY KEY (id)
);

/* ---------------------------------------------------
 Structure of table "daemonconfig" (lmsd config)
------------------------------------------------------*/
DROP SEQUENCE IF EXISTS daemonconfig_id_seq;
CREATE SEQUENCE daemonconfig_id_seq;
DROP TABLE IF EXISTS daemonconfig CASCADE;
CREATE TABLE daemonconfig (
    id 		integer 	DEFAULT nextval('daemonconfig_id_seq'::text) NOT NULL,
    instanceid 	integer 	DEFAULT 0 NOT NULL,
    var 	varchar(64) 	DEFAULT '' NOT NULL,
    value 	text 		DEFAULT '' NOT NULL,
    description text 		DEFAULT '' NOT NULL,
    disabled 	smallint 	DEFAULT 0 NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT daemonconfig_instanceid_key UNIQUE(instanceid, var)
);

/* ---------------------------------------------------
 Structure of table "docrights"
------------------------------------------------------*/
DROP SEQUENCE IF EXISTS docrights_id_seq;
CREATE SEQUENCE docrights_id_seq;
DROP TABLE IF EXISTS docrights CASCADE;
CREATE TABLE docrights (
    id          integer         DEFAULT nextval('docrights_id_seq'::text) NOT NULL,
    userid      integer         DEFAULT 0 NOT NULL,
    doctype     integer         DEFAULT 0 NOT NULL,
    rights      integer         DEFAULT 0 NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT docrights_userid_key UNIQUE (userid, doctype)
);

/* ---------------------------------------------------
 Structure of table "cashrights"
------------------------------------------------------*/
DROP SEQUENCE IF EXISTS cashrights_id_seq;
CREATE SEQUENCE cashrights_id_seq;
DROP TABLE IF EXISTS cashrights CASCADE;
CREATE TABLE cashrights (
    id 		integer 	DEFAULT nextval('cashrights_id_seq'::text) NOT NULL,
    userid 	integer 	DEFAULT 0 NOT NULL,
    regid 	integer 	DEFAULT 0 NOT NULL,
    rights 	integer 	DEFAULT 0 NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT cashrights_userid_key UNIQUE (userid, regid)
);

/* ---------------------------------------------------
 Structure of table "cashregs"
------------------------------------------------------*/
DROP SEQUENCE IF EXISTS cashregs_id_seq;
CREATE SEQUENCE cashregs_id_seq;
DROP TABLE IF EXISTS cashregs CASCADE;
CREATE TABLE cashregs (
    id 			integer 	DEFAULT nextval('cashregs_id_seq'::text) NOT NULL,
    name 		varchar(255) 	DEFAULT '' NOT NULL,
    description 	text 		DEFAULT '' NOT NULL,
    in_numberplanid 	integer 	DEFAULT 0 NOT NULL,
    out_numberplanid 	integer 	DEFAULT 0 NOT NULL,
    disabled 		smallint	DEFAULT 0 NOT NULL,
    PRIMARY KEY (id),
    UNIQUE (name)
);

/* ---------------------------------------------------
 Structure of table "cashreglog"
------------------------------------------------------*/
DROP SEQUENCE IF EXISTS cashreglog_id_seq;
CREATE SEQUENCE cashreglog_id_seq;
DROP TABLE IF EXISTS cashreglog CASCADE;
CREATE TABLE cashreglog (
    id 		integer 	DEFAULT nextval('cashreglog_id_seq'::text) NOT NULL,
    regid 	integer         DEFAULT 0 NOT NULL,
    userid 	integer		DEFAULT 0 NOT NULL,
    time 	integer		DEFAULT 0 NOT NULL,
    value 	numeric(9,2)    DEFAULT 0 NOT NULL,
    snapshot 	numeric(9,2)    DEFAULT 0 NOT NULL,
    description text		DEFAULT '' NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT cashreglog_regid_key UNIQUE (regid, time)
);

/* ---------------------------------------------------
 Structure of table "ewx_pt_config" (EtherWerX(R))
------------------------------------------------------*/
DROP SEQUENCE IF EXISTS ewx_pt_config_id_seq;
CREATE SEQUENCE ewx_pt_config_id_seq;
DROP TABLE IF EXISTS ewx_pt_config CASCADE;
CREATE TABLE ewx_pt_config (
    id 		integer 	DEFAULT nextval('ewx_pt_config_id_seq'::text) NOT NULL,
    nodeid 	integer         DEFAULT 0 NOT NULL,
    name 	varchar(32)     DEFAULT '' NOT NULL,
    mac 	varchar(20)     DEFAULT '' NOT NULL,
    ipaddr 	bigint          DEFAULT 0 NOT NULL,
    passwd 	varchar(32)     DEFAULT '' NOT NULL,
    PRIMARY KEY (id),
    UNIQUE (nodeid)
);

/* ---------------------------------------------------
 Structure of table "ewx_stm_nodes" (EtherWerX(R))
------------------------------------------------------*/
DROP SEQUENCE IF EXISTS ewx_stm_nodes_id_seq;
CREATE SEQUENCE ewx_stm_nodes_id_seq;
DROP TABLE IF EXISTS ewx_stm_nodes CASCADE;
CREATE TABLE ewx_stm_nodes (
        id 		integer		DEFAULT nextval('ewx_stm_nodes_id_seq'::text) NOT NULL,
	nodeid 		integer         DEFAULT 0 NOT NULL,
	mac 		varchar(20)     DEFAULT '' NOT NULL,
	ipaddr 		bigint          DEFAULT 0 NOT NULL,
	channelid 	integer       	DEFAULT 0 NOT NULL,
	uprate 		integer         DEFAULT 0 NOT NULL,
	upceil 		integer         DEFAULT 0 NOT NULL,
	downrate 	integer        	DEFAULT 0 NOT NULL,
	downceil 	integer        	DEFAULT 0 NOT NULL,
	halfduplex 	smallint     	DEFAULT 0 NOT NULL,
	PRIMARY KEY (id),
	UNIQUE (nodeid)
);

/* ---------------------------------------------------
 Structure of table "ewx_stm_channels" (EtherWerX(R))
------------------------------------------------------*/
DROP SEQUENCE IF EXISTS ewx_stm_channels_id_seq;
CREATE SEQUENCE ewx_stm_channels_id_seq;
DROP TABLE IF EXISTS ewx_stm_channels CASCADE;
CREATE TABLE ewx_stm_channels (
    id 		integer 	DEFAULT nextval('ewx_stm_channels_id_seq'::text) NOT NULL,
    cid 	integer      	DEFAULT 0 NOT NULL,
    upceil 	integer         DEFAULT 0 NOT NULL,
    downceil 	integer        	DEFAULT 0 NOT NULL,
    halfduplex  smallint    DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE (cid)
);

/* ---------------------------------------------------
 Structure of table "ewx_channels" (EtherWerX(R))
------------------------------------------------------*/
DROP SEQUENCE IF EXISTS ewx_channels_id_seq;
CREATE SEQUENCE ewx_channels_id_seq;
DROP TABLE IF EXISTS ewx_channels CASCADE;
CREATE TABLE ewx_channels (
    id 		integer 	DEFAULT nextval('ewx_channels_id_seq'::text) NOT NULL,
    name 	varchar(32)     DEFAULT '' NOT NULL,
    upceil 	integer         DEFAULT 0 NOT NULL,
    downceil 	integer        	DEFAULT 0 NOT NULL,
    upceil_n 	integer         DEFAULT NULL,
    downceil_n 	integer        	DEFAULT NULL,
    halfduplex  smallint    DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE (name)
);

/* ---------------------------------------------------
 Structure of table "netdevices"
----------------------------------------------------*/
DROP SEQUENCE IF EXISTS netdevices_id_seq;
CREATE SEQUENCE netdevices_id_seq;
DROP TABLE IF EXISTS netdevices CASCADE;
CREATE TABLE netdevices (
	id integer default nextval('netdevices_id_seq'::text) NOT NULL,
	name varchar(32) 	DEFAULT '' NOT NULL,
	location varchar(255) 	DEFAULT '' NOT NULL,
    location_city integer DEFAULT NULL
        REFERENCES location_cities (id) ON DELETE SET NULL ON UPDATE CASCADE,
    location_street integer DEFAULT NULL
        REFERENCES location_streets (id) ON DELETE SET NULL ON UPDATE CASCADE,
    location_house varchar(8) DEFAULT NULL,
    location_flat varchar(8) DEFAULT NULL,
	description text 	DEFAULT '' NOT NULL,
	producer varchar(64) 	DEFAULT '' NOT NULL,
	model varchar(32) 	DEFAULT '' NOT NULL,
	serialnumber varchar(32) DEFAULT '' NOT NULL,
	ports integer 		DEFAULT 0 NOT NULL,
	purchasetime integer	DEFAULT 0 NOT NULL,
	guaranteeperiod smallint DEFAULT 0,
	shortname varchar(32) 	DEFAULT '' NOT NULL,
	nastype integer 	DEFAULT 0 NOT NULL,
	clients integer 	DEFAULT 0 NOT NULL,
	secret varchar(60) 	DEFAULT '' NOT NULL,
	community varchar(50) 	DEFAULT '' NOT NULL,
	channelid integer 	DEFAULT NULL
	    REFERENCES ewx_channels (id) ON DELETE SET NULL ON UPDATE CASCADE,
	longitude numeric(10, 6) DEFAULT NULL,
	latitude numeric(10, 6) DEFAULT NULL,
	PRIMARY KEY (id)
);
CREATE INDEX netdevices_channelid_idx ON netdevices (channelid);
CREATE INDEX netdevices_location_street_idx ON netdevices (location_street);
CREATE INDEX netdevices_location_city_idx ON netdevices (location_city, location_street, location_house, location_flat);

/* ---------------------------------------------------
 Structure of table "dbinfo"
------------------------------------------------------*/
DROP TABLE IF EXISTS dbinfo CASCADE;
CREATE TABLE dbinfo (
    keytype 	varchar(255) 	DEFAULT '' NOT NULL,
    keyvalue 	varchar(255) 	DEFAULT '' NOT NULL,
    PRIMARY KEY (keytype)
);

/* ---------------------------------------------------
 Structure of table "imessengers"
------------------------------------------------------*/
DROP SEQUENCE IF EXISTS imessengers_id_seq;
CREATE SEQUENCE imessengers_id_seq;
DROP TABLE IF EXISTS imessengers CASCADE;
CREATE TABLE imessengers (
    id 		integer         DEFAULT nextval('imessengers_id_seq'::text) NOT NULL,
    customerid 	integer    	NOT NULL
	    REFERENCES customers (id) ON DELETE CASCADE ON UPDATE CASCADE,
    uid 	varchar(32)     DEFAULT '' NOT NULL,
    type 	smallint        DEFAULT 0 NOT NULL,
    PRIMARY KEY (id)
);
CREATE INDEX imessengers_customerid_idx ON imessengers (customerid);

/* ---------------------------------------------------
 Structure of table "customercontacts"
------------------------------------------------------*/
DROP SEQUENCE IF EXISTS customercontacts_id_seq;
CREATE SEQUENCE customercontacts_id_seq;
DROP TABLE IF EXISTS customercontacts CASCADE;
CREATE TABLE customercontacts (
    id 		integer 	DEFAULT nextval('customercontacts_id_seq'::text) NOT NULL,
    customerid 	integer 	NOT NULL
	    REFERENCES customers (id) ON DELETE CASCADE ON UPDATE CASCADE,
    name 	varchar(255) 	NOT NULL DEFAULT '',
    phone 	varchar(255) 	NOT NULL DEFAULT '',
    type    smallint        DEFAULT NULL,
    PRIMARY KEY (id)
);
CREATE INDEX customercontacts_customerid_idx ON customercontacts (customerid);
CREATE INDEX customercontacts_phone_idx ON customercontacts (phone);

/* ---------------------------------------------------
 Structure of table "excludedgroups"
------------------------------------------------------*/
DROP SEQUENCE IF EXISTS excludedgroups_id_seq;
CREATE SEQUENCE excludedgroups_id_seq;
DROP TABLE IF EXISTS excludedgroups CASCADE;
CREATE TABLE excludedgroups (
	id 		integer NOT NULL DEFAULT nextval('excludedgroups_id_seq'::text),
	customergroupid integer NOT NULL
	    REFERENCES customergroups (id) ON DELETE CASCADE ON UPDATE CASCADE,
	userid 		integer NOT NULL DEFAULT 0,
	PRIMARY KEY (id),
	CONSTRAINT excludedgroups_userid_key UNIQUE (userid, customergroupid)
);
CREATE INDEX excludedgroups_customergroupid_idx ON excludedgroups (customergroupid);

/* ---------------------------------------------------
 Structure of table "states"
------------------------------------------------------*/
DROP SEQUENCE IF EXISTS states_id_seq;
CREATE SEQUENCE states_id_seq;
DROP TABLE IF EXISTS states CASCADE;
CREATE TABLE states (
    	id 		integer 	DEFAULT nextval('states_id_seq'::text) NOT NULL,
	name 		varchar(255) 	NOT NULL DEFAULT '',
	description 	text 		NOT NULL DEFAULT '',
	PRIMARY KEY (id),
	UNIQUE (name)
);

/* ---------------------------------------------------
 Structure of table "countries"
------------------------------------------------------*/
DROP SEQUENCE IF EXISTS countries_id_seq;
CREATE SEQUENCE countries_id_seq;
DROP TABLE IF EXISTS countries CASCADE;
CREATE TABLE countries (
	id      integer DEFAULT nextval('countries_id_seq'::text) NOT NULL,
	name    varchar(255) NOT NULL DEFAULT '',
	PRIMARY KEY (id),
	UNIQUE (name)
);

/* ---------------------------------------------------
 Structure of table "zipcodes"
------------------------------------------------------*/
DROP SEQUENCE IF EXISTS zipcodes_id_seq;
CREATE SEQUENCE zipcodes_id_seq;
DROP TABLE IF EXISTS zipcodes CASCADE;
CREATE TABLE zipcodes (
    	id 		integer 	DEFAULT nextval('customerassignments_id_seq'::text) NOT NULL,
	zip 		varchar(10) 	NOT NULL DEFAULT '',
	stateid 	integer 	NOT NULL DEFAULT 0,
	PRIMARY KEY (id),
	UNIQUE (zip)
);
CREATE INDEX zipcodes_stateid_idx ON zipcodes (stateid);

/* ---------------------------------------------------
 Structure of table "divisions"
------------------------------------------------------*/
DROP SEQUENCE IF EXISTS divisions_id_seq;
CREATE SEQUENCE divisions_id_seq;
DROP TABLE IF EXISTS divisions CASCADE;
CREATE TABLE divisions (
    	id 		integer 	NOT NULL DEFAULT nextval('divisions_id_seq'::text),
	shortname 	varchar(255) 	NOT NULL DEFAULT '',
	name 		text 		NOT NULL DEFAULT '',
	address		varchar(255) 	NOT NULL DEFAULT '',
	city		varchar(255) 	NOT NULL DEFAULT '',
	zip		varchar(255) 	NOT NULL DEFAULT '',
	countryid	integer		NOT NULL DEFAULT 0,
	ten		varchar(16)	NOT NULL DEFAULT '',
	regon		varchar(255)	NOT NULL DEFAULT '',
	account		varchar(48) 	NOT NULL DEFAULT '',
	inv_header 	text		NOT NULL DEFAULT '',
	inv_footer 	text		NOT NULL DEFAULT '',
	inv_author	text		NOT NULL DEFAULT '',
	inv_cplace	text		NOT NULL DEFAULT '',
	inv_paytime	smallint	DEFAULT NULL,
	inv_paytype	smallint	DEFAULT NULL,
	description 	text		NOT NULL DEFAULT '',
	status 		smallint 	NOT NULL DEFAULT 0,
	PRIMARY KEY (id),
	UNIQUE (shortname)
);

/* ---------------------------------------------------
 Structure of table "voipaccounts"
------------------------------------------------------*/
DROP SEQUENCE IF EXISTS voipaccounts_id_seq;
CREATE SEQUENCE voipaccounts_id_seq;
DROP TABLE IF EXISTS voipaccounts CASCADE;
CREATE TABLE voipaccounts (
	id		integer		NOT NULL DEFAULT nextval('voipaccounts_id_seq'::text),
	ownerid		integer		NOT NULL DEFAULT 0,
	login		varchar(255)	NOT NULL DEFAULT '',
	passwd		varchar(255)	NOT NULL DEFAULT '',
	phone		varchar(255)	NOT NULL DEFAULT '',
	access      smallint        NOT NULL DEFAULT 1,
	creationdate	integer		NOT NULL DEFAULT 0,
	moddate		integer		NOT NULL DEFAULT 0,
	creatorid	integer		NOT NULL DEFAULT 0,
	modid		integer		NOT NULL DEFAULT 0,
	PRIMARY KEY (id)
);

/* ---------------------------------------------------
 Structure of table "messages"
------------------------------------------------------*/
DROP SEQUENCE IF EXISTS messages_id_seq;
DROP TABLE IF EXISTS messages CASCADE;
CREATE SEQUENCE messages_id_seq;
CREATE TABLE messages (
        id 	integer 	DEFAULT nextval('messages_id_seq'::text) NOT NULL,
        subject varchar(255)	DEFAULT '' NOT NULL,
	body 	text		DEFAULT '' NOT NULL,
	cdate 	integer		DEFAULT 0 NOT NULL,
	type 	smallint	DEFAULT 0 NOT NULL,
	userid 	integer		DEFAULT 0 NOT NULL,
	sender 	varchar(255) 	DEFAULT NULL,
        PRIMARY KEY (id)
);

CREATE INDEX messages_cdate_idx ON messages (cdate, type);
CREATE INDEX messages_userid_idx ON messages (userid);

/* ---------------------------------------------------
 Structure of table "messageitems"
------------------------------------------------------*/
DROP SEQUENCE IF EXISTS messageitems_id_seq;
DROP TABLE IF EXISTS messageitems CASCADE;
CREATE SEQUENCE messageitems_id_seq;
CREATE TABLE messageitems (
        id 		integer 	DEFAULT nextval('messageitems_id_seq'::text) NOT NULL,
	messageid 	integer		DEFAULT 0 NOT NULL,
	customerid 	integer 	DEFAULT 0 NOT NULL,
	destination 	varchar(255) 	DEFAULT '' NOT NULL,
	lastdate 	integer		DEFAULT 0 NOT NULL,
	status 		smallint	DEFAULT 0 NOT NULL,
	error 		text		DEFAULT NULL, 
        PRIMARY KEY (id)
); 

CREATE INDEX messageitems_messageid_idx ON messageitems (messageid);
CREATE INDEX messageitems_customerid_idx ON messageitems (customerid);

/* ---------------------------------------------------
 Structure of table "nastypes"
------------------------------------------------------*/
DROP SEQUENCE IF EXISTS nastypes_id_seq;
CREATE SEQUENCE nastypes_id_seq;
DROP TABLE IF EXISTS nastypes CASCADE;
CREATE TABLE nastypes (
    	id 	integer 	DEFAULT nextval('nastypes_id_seq'::text) NOT NULL,
	name 	varchar(255) 	NOT NULL,
	PRIMARY KEY (id),
	UNIQUE (name)
);

/* ---------------------------------------------------
 Structure of table "managementurls"
------------------------------------------------------*/
DROP SEQUENCE IF EXISTS managementurls_id_seq;
CREATE SEQUENCE managementurls_id_seq;
DROP TABLE IF EXISTS managementurls;
CREATE TABLE managementurls (
	id integer		DEFAULT nextval('managementurls_id_seq'::text) NOT NULL,
	netdevid integer	NOT NULL
		REFERENCES netdevices (id) ON DELETE CASCADE ON UPDATE CASCADE,
	url text		DEFAULT '' NOT NULL,
	comment varchar(100)	DEFAULT NULL,
	PRIMARY KEY (id)
);

/* ---------------------------------------------------
 Structure of table "up_rights" (Userpanel)
------------------------------------------------------*/
DROP SEQUENCE IF EXISTS up_rights_id_seq;
CREATE SEQUENCE up_rights_id_seq;
DROP TABLE IF EXISTS up_rights CASCADE;
CREATE TABLE up_rights (
	id integer 		DEFAULT nextval('up_rights_id_seq'::text) NOT NULL,
        module varchar(255) 	DEFAULT 0 NOT NULL,
        name varchar(255) 	DEFAULT 0 NOT NULL,
        description varchar(255) DEFAULT 0,
	setdefault smallint 	DEFAULT 0,
	PRIMARY KEY (id)
);

/* ---------------------------------------------------
 Structure of table "up_rights_assignments" (Userpanel)
------------------------------------------------------*/
DROP SEQUENCE IF EXISTS up_rights_assignments_id_seq;
CREATE SEQUENCE up_rights_assignments_id_seq;
DROP TABLE IF EXISTS up_rights_assignments CASCADE;
CREATE TABLE up_rights_assignments (
	id integer 		DEFAULT nextval('up_rights_assignments_id_seq'::text) NOT NULL,
	customerid integer 	DEFAULT 0 NOT NULL,
        rightid integer 	DEFAULT 0 NOT NULL,
	PRIMARY KEY (id),
	CONSTRAINT up_rights_assignments_customerid_key UNIQUE (customerid, rightid)
);

/* ---------------------------------------------------
 Structure of table "up_customers" (Userpanel)
------------------------------------------------------*/
DROP SEQUENCE IF EXISTS up_customers_id_seq;
CREATE SEQUENCE up_customers_id_seq;
DROP TABLE IF EXISTS up_customers CASCADE;
CREATE TABLE up_customers (
	id integer 		        DEFAULT nextval('up_customers_id_seq'::text) NOT NULL,
    customerid integer 	    DEFAULT 0 NOT NULL,
	lastlogindate integer 	DEFAULT 0 NOT NULL,
	lastloginip varchar(16) DEFAULT '' NOT NULL,
	failedlogindate integer DEFAULT 0 NOT NULL,
	failedloginip varchar(16) DEFAULT '' NOT NULL,
	enabled smallint 	    DEFAULT 0 NOT NULL,
	PRIMARY KEY (id)
);

/* ---------------------------------------------------
 Structure of table "up_help" (Userpanel)
------------------------------------------------------*/
DROP SEQUENCE IF EXISTS up_help_id_seq;
CREATE SEQUENCE up_help_id_seq;
DROP TABLE IF EXISTS up_help CASCADE;
CREATE TABLE up_help (
    id integer 		    DEFAULT nextval('up_help_id_seq'::text) NOT NULL,
	reference integer 	DEFAULT 0 NOT NULL,
	title varchar(128) 	DEFAULT 0 NOT NULL,
	body text 		    DEFAULT '' NOT NULL,
	PRIMARY KEY (id)
);

/* ---------------------------------------------------
 Structure of table "up_info_changes" (Userpanel)
------------------------------------------------------*/
DROP SEQUENCE IF EXISTS up_info_changes_id_seq;
CREATE SEQUENCE up_info_changes_id_seq;
DROP TABLE IF EXISTS up_info_changes CASCADE;
CREATE TABLE up_info_changes (
	id integer 		DEFAULT nextval('up_info_changes_id_seq'::text) NOT NULL,
	customerid integer 	DEFAULT 0 NOT NULL,
	fieldname varchar(255) 	DEFAULT 0 NOT NULL,
	fieldvalue varchar(255) DEFAULT 0 NOT NULL,
	PRIMARY KEY (id)
);

DROP SEQUENCE IF EXISTS syslog_id_seq;
DROP TABLE IF EXISTS syslog CASCADE;
CREATE SEQUENCE syslog_id_seq;
CREATE TABLE syslog (
    id bigint DEFAULT nextval('syslog_id_seq'::text) NOT NULL,
    cdate integer default 0 not null,
    uid integer default null,
    cid integer default null,
    nid integer default null,
    oid integer DEFAULT NULL,
    module smallint default null,
    event smallint default null,
    msg varchar(255) default null,
    diff text default null,
    PRIMARY KEY (id)
);
CREATE INDEX syslog_cdate_idx ON syslog(cdate);
CREATE INDEX syslog_uid_idx ON syslog(uid);
CREATE INDEX syslog_module_idx ON syslog(module);
CREATE INDEX syslog_event_idx ON syslog(event);


DROP VIEW IF EXISTS monit_vnodes;
DROP SEQUENCE IF EXISTS monittime_id_seq;
DROP SEQUENCE IF EXISTS monitown_id_seq;
DROP SEQUENCE IF EXISTS monitwarn_id_seq;
DROP TABLE IF EXISTS monitnodes CASCADE;
DROP TABLE IF EXISTS monittime CASCADE;
DROP TABLE IF EXISTS monitown CASCADE;
DROP TABLE IF EXISTS monituser CASCADE;
DROP TABLE IF EXISTS monitwarn CASCADE;

CREATE TABLE monitnodes (
	id integer		DEFAULT '0' NOT NULL,
	test_type varchar(15)	DEFAULT 'icmp' NOT NULL,
	test_port integer	DEFAULT NULL,
	active smallint 	DEFAULT '1' NOT NULL,
	send_timeout smallint	DEFAULT NULL,
	send_ptime smallint 	default null,
	maxptime integer	DEFAULT NULL,
	PRIMARY KEY (id)
);
CREATE INDEX monitnodes_active_idx ON monitnodes (active);

CREATE SEQUENCE monittime_id_seq;
CREATE TABLE monittime (
	id bigint		DEFAULT nextval('monittime_id_seq'::text) NOT NULL,
	nodeid integer		DEFAULT NULL,
	ownid integer		DEFAULT NULL,
	cdate integer 		DEFAULT '0' NOT NULL,
	ptime decimal(10,3) 	DEFAULT '0.000' NOT NULL,
	warn_ptime		smallint default '0' not null,
	warn_timeout		smallint default '0' not null,
	PRIMARY KEY (id)
);
CREATE INDEX monittime_nodeid_idx ON monittime (nodeid);
CREATE INDEX monittime_ownid_idx ON monittime (ownid);

CREATE SEQUENCE monitown_id_seq;
CREATE TABLE monitown (
	id integer 		DEFAULT nextval('monitown_id_seq'::text) NOT NULL,
	ipaddr varchar(100) 	DEFAULT NULL,
	name varchar(50) 	DEFAULT NULL,
	description varchar(255) DEFAULT NULL,
	test_type varchar(15)	DEFAULT 'icmp' NOT NULL,
	test_port integer	DEFAULT NULL,
	active smallint 	DEFAULT '1' NOT NULL,
	send_timeout smallint	DEFAULT NULL,
	send_ptime smallint	DEFAULT NULL,
	maxptime integer	DEFAULT NULL,
	PRIMARY KEY (id)
);
CREATE INDEX monitown_active_idx ON monitown (active);

CREATE TABLE monituser (
	id integer 		DEFAULT '0' NOT NULL,
	sendemail smallint 	DEFAULT '0' NOT NULL,
	sendphone smallint 	DEFAULT '0' NOT NULL,
	sendgg smallint		DEFAULT '0' NOT NULL,
	active smallint 	DEFAULT '1' NOT NULL,
	description text 	DEFAULT NULL,
	PRIMARY KEY (id)
);

CREATE SEQUENCE monitwarn_id_seq;
CREATE TABLE monitwarn (
	    id bigint 		DEFAULT nextval('monitwarn_id_seq'::text) NOT NULL,
	    nodeid integer 	DEFAULT NULL,
	    ownid integer	DEFAULT NULL,
	    monitid bigint 	DEFAULT NULL,
	    cdate integer 	DEFAULT '0' NOT NULL,
	    backtime integer 	DEFAULT '0' NOT NULL,
	    sendwarn smallint 	DEFAULT '0' NOT NULL,
	    sendback smallint 	DEFAULT '0' NOT NULL,
	    PRIMARY KEY (id)
);
CREATE INDEX monitwarn_monitid_idx ON monitwarn (monitid);

DROP SEQUENCE IF EXISTS info_center_post_id_seq;
DROP SEQUENCE IF EXISTS info_center_id_seq;
DROP table IF EXISTS info_center_post CASCADE;
DROP TABLE IF EXISTS info_center CASCADE;

CREATE SEQUENCE info_center_id_seq;
CREATE TABLE info_center ( 
	id integer default nextval('info_center_id_seq'::text) NOT NULL,
	cid integer default null,
	nid integer default null,
	netdevid integer default null,
	topic varchar(255) default '',
	description varchar(255) default null,
	cdate integer default 0,
	mdate integer default 0,
	cuser integer default 0,
	muser integer default 0,
	closed smallint default 0,
	closeddate integer default 0,
	closedinfo text default null,
	closeduser integer default 0,
	deleted smallint default 0,
	prio smallint default 1,
	primary key(id)
);
CREATE INDEX info_center_cid_idx ON info_center (cid);
CREATE INDEX info_center_cdate_idx ON info_center (cdate);
CREATE INDEX info_center_deleted_idx ON info_center (deleted);

CREATE SEQUENCE info_center_post_id_seq;
create table info_center_post (
	id bigint default nextval('info_center_post_id_seq'::text) not null,
	infoid integer not null references info_center (id) on delete cascade on update cascade,
	post text default null,
	cdate integer default 0,
	mdate integer default 0,
	cuser integer default 0,
	muser integer default 0,
	primary key(id)
);
CREATE INDEX info_center_post_infoid_idx ON info_center_post (infoid);


DROP SEQUENCE IF EXISTS hv_assign_id_seq;
DROP SEQUENCE IF EXISTS hv_billing_id_seq;

DROP TABLE IF EXISTS hv_enduserlist CASCADE;
DROP TABLE IF EXISTS hv_terminal CASCADE;
DROP TABLE IF EXISTS hv_subscriptionlist CASCADE;
DROP TABLE IF EXISTS hv_pstnusage CASCADE;
DROP TABLE IF EXISTS hv_pstnrange CASCADE;
DROP TABLE IF EXISTS hv_pstn CASCADE;
DROP TABLE IF EXISTS hv_pricelist CASCADE;
DROP TABLE IF EXISTS hv_customers CASCADE;
DROP TABLE IF EXISTS hv_pcb CASCADE;
DROP TABLE IF EXISTS hv_province CASCADE;
DROP TABLE IF EXISTS hv_county CASCADE;
DROP TABLE IF EXISTS hv_borough CASCADE;
DROP TABLE IF EXISTS hv_billing CASCADE;
DROP TABLE IF EXISTS hv_assign CASCADE;
DROP TYPE IF EXISTS admin_enum;
DROP TYPE IF EXISTS subscription_used_enum;
DROP TYPE IF EXISTS success_call_enum;
DROP TYPE IF EXISTS screen_numbers_enum;
DROP TYPE IF EXISTS t38_fax_enum;
DROP TYPE IF EXISTS ussage_enum;
DROP TYPE IF EXISTS is_main_enum;
DROP TYPE IF EXISTS disa_enabled_enum;
DROP TYPE IF EXISTS clir_enum;
DROP TYPE IF EXISTS virtual_fax_enum;
DROP TYPE IF EXISTS voicemail_enabled_enum;
DROP TYPE IF EXISTS charge_internal_call_enum;
DROP TYPE IF EXISTS issue_invoice_enum;
DROP TYPE IF EXISTS payment_type_enum;
DROP TYPE IF EXISTS is_wlr_enum;
DROP TYPE IF EXISTS active_enum;
DROP TYPE IF EXISTS consent_data_processing_enum;
DROP TYPE IF EXISTS open_registration_enum;
DROP TYPE IF EXISTS is_removed_enum;
CREATE TYPE admin_enum AS ENUM('t','f');
CREATE TYPE subscription_used_enum AS ENUM('f','t');
CREATE TYPE success_call_enum AS ENUM('f','t');
CREATE TYPE screen_numbers_enum AS ENUM('t','f');
CREATE TYPE t38_fax_enum AS ENUM('f','t');
CREATE TYPE ussage_enum AS ENUM('t','f');
CREATE TYPE is_main_enum AS ENUM('t','f');
CREATE TYPE disa_enabled_enum AS ENUM('f','t');
CREATE TYPE clir_enum AS ENUM('f','t');
CREATE TYPE virtual_fax_enum AS ENUM('f','t');
CREATE TYPE voicemail_enabled_enum AS ENUM('t','f');
CREATE TYPE charge_internal_call_enum AS ENUM('f','t');
CREATE TYPE issue_invoice_enum AS ENUM('f','t');
CREATE TYPE payment_type_enum AS ENUM('prepaid','postpaid');
CREATE TYPE is_wlr_enum AS ENUM('f','t');
CREATE TYPE active_enum AS ENUM('f','t');
CREATE TYPE consent_data_processing_enum AS ENUM('f','t');
CREATE TYPE open_registration_enum AS ENUM('f','t');
CREATE TYPE is_removed_enum AS ENUM('f','t');


CREATE SEQUENCE hv_assign_id_seq;
CREATE TABLE hv_assign (
	id integer DEFAULT nextval('hv_assign_id_seq'::text) NOT NULL,
	customerid integer DEFAULT NULL,
	keytype varchar(30) DEFAULT NULL,
	keyvalue varchar(255) DEFAULT NULL,
	PRIMARY KEY(id)
);
CREATE INDEX hv_assign_customerid_idx ON hv_assign (customerid);

CREATE SEQUENCE hv_billing_id_seq;
CREATE TABLE hv_billing (
	id integer DEFAULT nextval('hv_billing_id_seq'::text) NOT NULL,
	customerid integer DEFAULT NULL,
	rel_cause integer DEFAULT NULL,
	start_time varchar(30) DEFAULT NULL,
	start_time_unix integer DEFAULT NULL,
	customer_name varchar(255) DEFAULT NULL,
	terminal_name varchar(255) DEFAULT NULL,
	ext_billing_id integer DEFAULT NULL,
	caller varchar(255) DEFAULT NULL,
	bill_cpb varchar(255) DEFAULT NULL,
	duration integer DEFAULT NULL,
	calltype varchar(50) DEFAULT NULL,
	country varchar(255) DEFAULT NULL,
	description varchar(255) DEFAULT NULL,
	operator varchar(255) DEFAULT NULL,
	type varchar(30) DEFAULT NULL,
	cost numeric(12,4) DEFAULT 0 NOT NULL,
	price numeric(12,4) DEFAULT 0 NOT NULL,
	init_charge numeric(12,4) DEFAULT 0 NOT NULL,
	reseller_price numeric(12,4) DEFAULT 0 NOT NULL,
	reseller_cost numeric(12,4) DEFAULT 0 NOT NULL,
	reseller_init_charge numeric(12,4) DEFAULT 0 NOT NULL,
	margin numeric(12,4) DEFAULT 0 NOT NULL,
	subscription_used subscription_used_enum,
	platform_type varchar(20) DEFAULT NULL,
	success_call success_call_enum,
	PRIMARY KEY (id)
);
CREATE INDEX hv_billing_customerid_idx ON hv_billing (customerid);
CREATE INDEX hv_billing_success_call_idx ON hv_billing (success_call);

CREATE TABLE hv_borough (
	id integer DEFAULT 0 NOT NULL,
	name varchar(50) DEFAULT NULL,
	PRIMARY KEY(id)
);

CREATE TABLE hv_county (
	id integer DEFAULT 0 NOT NULL,
	name varchar(50) DEFAULT NULL,
	PRIMARY KEY(id)
);

CREATE TABLE hv_province (
	id integer DEFAULT 0 NOT NULL,
	name varchar(50) DEFAULT NULL,
	PRIMARY KEY(id)
);

CREATE TABLE hv_pcb (
	id integer DEFAULT 0 NOT NULL,
	province integer DEFAULT 0 NOT NULL,
	county integer DEFAULT 0 NOT NULL,
	borough integer DEFAULT 0 NOT NULL,
	PRIMARY KEY(id)
);

CREATE TABLE hv_customers (
	id integer DEFAULT 0 NOT NULL,
	name varchar(255) DEFAULT NULL,
	id_reseller integer DEFAULT NULL,
	email varchar(255) DEFAULT NULL,
	address varchar(255) DEFAULT NULL,
	street_number varchar(10) DEFAULT NULL,
	apartment_number varchar(10) DEFAULT NULL,
	postcode varchar(10) DEFAULT NULL,
	city varchar(255) DEFAULT NULL,
	country varchar(255) DEFAULT NULL,
	b_name varchar(255) DEFAULT NULL,
	b_address varchar(255) DEFAULT NULL,
	b_street_number varchar(10) DEFAULT NULL,
	b_apartment_number varchar(10) DEFAULT NULL,
	b_postcode varchar(10) DEFAULT NULL,
	b_city varchar(255) DEFAULT NULL,
	b_country varchar(255) DEFAULT NULL,
	b_nip varchar(50) DEFAULT NULL,
	b_regon varchar(50) DEFAULT NULL,
	ext_billing_id integer DEFAULT NULL,
	issue_invoice issue_invoice_enum,
	id_default_pricelist integer DEFAULT NULL,
	id_default_balance integer DEFAULT NULL,
	payment_type payment_type_enum,
	is_wlr is_wlr_enum,
	active active_enum,
	create_date varchar(30) DEFAULT NULL,
	consent_data_processing consent_data_processing_enum,
	platform_user_add_stamp varchar(50) DEFAULT NULL,
	open_registration open_registration_enum,
	is_removed is_removed_enum,
	PRIMARY KEY(id)
);

CREATE TABLE hv_pricelist (
	id integer DEFAULT 0 NOT NULL,
	name varchar(50) DEFAULT NULL,
	charge_internal_call charge_internal_call_enum,
	PRIMARY KEY(id)
);

CREATE TABLE hv_pstn (
	id integer DEFAULT 0 NOT NULL,
	customerid integer DEFAULT 0 NOT NULL REFERENCES hv_customers (id) ON DELETE CASCADE ON UPDATE CASCADE,
	extension varchar(30) DEFAULT NULL,
	country_code varchar(3) DEFAULT NULL,
	number varchar(20) DEFAULT NULL,
	is_main is_main_enum,
	disa_enabled disa_enabled_enum,
	clir clir_enum,
	virtual_fax virtual_fax_enum,
	terminal_name varchar(100) DEFAULT NULL,
	id_auth integer DEFAULT NULL,
	create_date varchar(30) DEFAULT NULL,
	voicemail_enabled voicemail_enabled_enum,
	PRIMARY KEY(id)
);
CREATE INDEX hv_pstn_customerid_idx ON hv_pstn (customerid);

CREATE TABLE hv_pstnrange (
	id integer DEFAULT 0 NOT NULL,
	range_start bigint DEFAULT NULL,
	range_end bigint DEFAULT NULL,
	description varchar(255) DEFAULT NULL,
	id_reseller integer DEFAULT NULL,
	country_code varchar(6) DEFAULT NULL,
	open_registration open_registration_enum,
	ussage ussage_enum,
	PRIMARY KEY(id)
);

CREATE TABLE hv_pstnusage (
	extension bigint DEFAULT 0 NOT NULL,
	number integer DEFAULT NULL,
	customerid integer DEFAULT NULL,
	country_code varchar(6) DEFAULT NULL,
	customer_name varchar(255) DEFAULT NULL,
	idrange integer DEFAULT NULL,
	PRIMARY KEY(extension)
);
CREATE INDEX hv_pstnusage_number_idx ON hv_pstnusage (number);
CREATE INDEX hv_pstnusage_idrange_idx ON hv_pstnusage (idrange);

CREATE TABLE hv_subscriptionlist (
	id integer DEFAULT 0 NOT NULL,
	name varchar(255) DEFAULT NULL,
	value numeric(9,2) DEFAULT 0 NOT NULL,
	f_dld varchar(255) DEFAULT NULL,
	f_mobile varchar(255) DEFAULT NULL,
	f_ild varchar(255) DEFAULT NULL,
	id_reseller integer DEFAULT NULL,
	invoice_value numeric(9,2) DEFAULT 0 NOT NULL,
	PRIMARY KEY(id)
);

CREATE TABLE hv_terminal (
	id integer DEFAULT 0 NOT NULL,
	customerid integer DEFAULT 0 NOT NULL REFERENCES hv_customers (id) ON DELETE CASCADE ON UPDATE CASCADE,
	username varchar(100) DEFAULT NULL,
	password varchar(100) DEFAULT NULL,
	screen_numbers screen_numbers_enum,
	t38_fax t38_fax_enum,
	customer_name varchar(255) DEFAULT NULL,
	id_pricelist integer DEFAULT NULL,
	pricelist_name varchar(255) DEFAULT NULL,
	balance_value numeric(9,2) DEFAULT 0 NOT NULL,
	id_auth integer DEFAULT NULL,
	id_subscription integer DEFAULT NULL,
	subscription_from varchar(50) DEFAULT NULL,
	subscription_to varchar(50) DEFAULT NULL,
	value_left numeric(9,2) DEFAULT 0 NOT NULL,
	id_terminal_location integer DEFAULT NULL,
	area_code varchar(10) DEFAULT NULL,
	borough varchar(255) DEFAULT NULL,
	county varchar(255) DEFAULT NULL,
	province varchar(255) DEFAULT NULL,
	sip_proxy varchar(255) DEFAULT NULL,
	subscriptions varchar(255) DEFAULT NULL,
	extensions text DEFAULT NULL,
	PRIMARY KEY (id)
);
CREATE INDEX hv_terminal_customerid_idx ON hv_terminal (customerid);

CREATE TABLE hv_enduserlist (
	id integer DEFAULT 0 NOT NULL,
	customerid integer DEFAULT 0 NOT NULL REFERENCES hv_customers (id) ON DELETE CASCADE ON UPDATE CASCADE,
	password varchar(255) DEFAULT NULL,
	email varchar(255) DEFAULT NULL,
	admin admin_enum,
	vm_count integer DEFAULT NULL,
	fax_count integer DEFAULT NULL,
	exten_count integer DEFAULT NULL,
	vexten_count integer DEFAULT NULL,
	PRIMARY KEY (id)
);
CREATE INDEX hv_enduserlist_customerid_idx ON hv_enduserlist (customerid);

INSERT INTO hv_province (id, name) VALUES
    (1, 'dolnośląskie'),(2, 'kujawsko-pomorskie'),(3, 'lubelskie'),(4, 'lubuskie'),(5, 'łódzkie'),(6, 'małopolskie'),(7, 'mazowieckie'),(8, 'opolskie'),(9, 'podkarpackie'),
    (10, 'podlaskie'),(11, 'pomorskie'),(12, 'śląskie'),(13, 'świętokrzyskie'),(14, 'warmińsko-mazurskie'),(15, 'wielkopolskie'),(16, 'zachodniopomorskie');




INSERT INTO hv_county (id, name) VALUES (28, 'aleksandrowski'),(257, 'augustowski'),(344, 'bartoszycki'),(128, 'bełchatowski'),(297, 'będziński'),(91, 'bialski'),(92, 'Biała Podlaska'),
    (175, 'białobrzeski'),(402, 'białogardzki'),(258, 'białostocki'),(259, 'Białystok'),(260, 'bielski'),(298, 'Bielsko-Biała'),(299, 'bieruńsko-lędziński'),(231, 'bieszczadzki'),
    (93, 'biłgorajski'),(153, 'bocheński'),(1, 'bolesławiecki'),(345, 'braniewski'),(29, 'brodnicki'),(154, 'brzeski'),(129, 'brzeziński'),(232, 'brzozowski'),(332, 'buski'),
    (30, 'bydgoski'),(31, 'Bydgoszcz'),(300, 'Bytom'),(274, 'bytowski'),(94, 'Chełm'),(32, 'chełmiński'),(95, 'chełmski'),(364, 'chodzieski'),(275, 'chojnicki'),(301, 'Chorzów'),
    (403, 'choszczeński'),(155, 'chrzanowski'),(176, 'ciechanowski'),(302, 'cieszyński'),(365, 'czarnkowsko-trzcianecki'),(303, 'Częstochowa'),(304, 'częstochowski'),(276, 'człuchowski'),
    (305, 'Dąbrowa Górnicza'),(156, 'dąbrowski'),(233, 'dębicki'),(404, 'drawski'),(346, 'działdowski'),(423, 'dzierżoniowski'),(347, 'Elbląg'),(348, 'elbląski'),(349, 'ełcki'),
    (177, 'garwoliński'),(277, 'Gdańsk'),(278, 'gdański'),(279, 'Gdynia'),(350, 'giżycki'),(306, 'Gliwice'),(307, 'gliwicki'),(2, 'głogowski'),(219, 'głubczycki'),(366, 'gnieźnieński'),
    (405, 'goleniowski'),(33, 'golubsko-dobrzyński'),(351, 'gołdapski'),(157, 'gorlicki'),(114, 'gorzowski'),(115, 'Gorzów Wielkopolski'),(178, 'gostyniński'),(369, 'gostyński'),
    (3, 'górowski'),(261, 'grajewski'),(179, 'grodziski'),(180, 'grójecki'),(34, 'Grudziądz'),(35, 'grudziądzki'),(406, 'gryficki'),(407, 'gryfiński'),(262, 'hajnowski'),
    (96, 'hrubieszowski'),(352, 'iławski'),(36, 'inowrocławski'),(97, 'janowski'),(370, 'jarociński'),(234, 'jarosławski'),(235, 'jasielski'),(308, 'Jastrzębie-Zdrój'),(4, 'jaworski'),
    (309, 'Jaworzno'),(5, 'Jelenia Góra'),(6, 'jeleniogórski'),(333, 'jędrzejowski'),(371, 'kaliski'),(372, 'Kalisz'),(7, 'kamiennogórski'),(408, 'kamieński'),(280, 'kartuski'),
    (310, 'Katowice'),(334, 'kazimierski'),(220, 'kędzierzyńsko-kozielski'),(373, 'kępiński'),(353, 'kętrzyński'),(335, 'Kielce'),(336, 'kielecki'),(221, 'kluczborski'),(311, 'kłobucki');



INSERT INTO hv_county (id, name) VALUES (8, 'kłodzki'),(236, 'kolbuszowski'),(263, 'kolneński'),(374, 'kolski'),(409, 'kołobrzeski'),(427, 'konecki'),(375, 'Konin'),(376, 'koniński'),
    (410, 'Koszalin'),(411, 'koszaliński'),(377, 'kościański'),(281, 'kościerski'),(181, 'kozienicki'),(158, 'krakowski'),(159, 'Kraków'),(222, 'krapkowicki'),(98, 'krasnostawski'),
    (99, 'kraśnicki'),(239, 'Krosno'),(116, 'krośnieński'),(378, 'krotoszyński'),(130, 'kutnowski'),(282, 'kwidzyński'),(182, 'legionowski'),(9, 'Legnica'),(10, 'legnicki'),(240, 'leski'),
    (379, 'leszczyński'),(380, 'Leszno'),(241, 'leżajski'),(283, 'lęborski'),(354, 'lidzbarski'),(160, 'limanowski'),(37, 'lipnowski'),(183, 'lipski'),(242, 'lubaczowski'),(11, 'lubański'),
    (100, 'lubartowski'),(101, 'lubelski'),(425, 'lubiński'),(102, 'Lublin'),(312, 'lubliniecki'),(12, 'lwówecki'),(243, 'łańcucki'),(131, 'łaski'),(132, 'łęczycki'),(103, 'łęczyński'),
    (412, 'łobeski'),(264, 'Łomża'),(265, 'łomżycki'),(426, 'łomżyński'),(184, 'łosicki'),(133, 'łowicki'),(134, 'łódzki wschodni'),(135, 'Łódź'),(104, 'łukowski'),(185, 'makowski'),
    (284, 'malborski'),(161, 'miechowski'),(244, 'mielecki'),(381, 'międzychodzki'),(117, 'międzyrzecki'),(313, 'mikołowski'),(13, 'milicki'),(186, 'miński'),(187, 'mławski'),
    (38, 'mogileński'),(266, 'moniecki'),(355, 'mrągowski'),(314, 'Mysłowice'),(315, 'myszkowski'),(162, 'myślenicki'),(413, 'myśliborski'),(39, 'nakielski'),(223, 'namysłowski'),
    (356, 'nidzicki'),(245, 'niżański'),(188, 'nowodworski'),(357, 'nowomiejski'),(163, 'nowosądecki'),(118, 'nowosolski'),(164, 'nowotarski'),(382, 'nowotomyski'),(165, 'Nowy Sącz'),
    (224, 'nyski'),(385, 'obornicki'),(358, 'olecki'),(225, 'oleski'),(14, 'oleśnicki'),(166, 'olkuski'),(359, 'Olsztyn'),(360, 'olsztyński'),(15, 'oławski'),(337, 'opatowski'),
    (136, 'opoczyński'),(226, 'Opole'),(105, 'opolski'),(189, 'ostrołęcki'),(190, 'Ostrołęka'),(428, 'ostrowiecki'),(191, 'ostrowski'),(361, 'ostródzki'),(386, 'ostrzeszowski'),
    (167, 'oświęcimski'),(192, 'otwocki'),(137, 'pabianicki'),(138, 'pajęczański'),(106, 'parczewski'),(193, 'piaseczyński'),(316, 'Piekary Śląskie'),(387, 'pilski'),(339, 'pińczowski'),
    (140, 'Piotrków Trybunalski'),(139, 'piotrowski'),(362, 'piski'),(388, 'pleszewski'),(194, 'Płock'),(195, 'płocki'),(196, 'płoński'),(141, 'poddębicki'),(414, 'policki'),(16, 'polkowicki');



INSERT INTO hv_county (id, name) VALUES (389, 'Poznań'),(390, 'poznański'),(168, 'proszowicki'),(229, 'prudnicki'),(197, 'pruszkowski'),(198, 'przasnyski'),(246, 'przemyski'),(247, 'Przemyśl'),
    (248, 'przeworski'),(199, 'przysuski'),(317, 'pszczyński'),(287, 'pucki'),(107, 'puławski'),(200, 'pułtuski'),(415, 'pyrzycki'),(318, 'raciborski'),(201, 'Radom'),(202, 'radomski'),
    (142, 'radomszczański'),(40, 'radziejowski'),(108, 'radzyński'),(391, 'rawicki'),(143, 'rawski'),(249, 'ropczycko-sędziszowski'),(319, 'Ruda Śląska'),(320, 'rybnicki'),(321, 'Rybnik'),
    (109, 'rycki'),(41, 'rypiński'),(250, 'rzeszowski'),(251, 'Rzeszów'),(338, 'sandomierski'),(252, 'sanocki'),(267, 'sejneński'),(82, 'sępoleński'),(203, 'Siedlce'),(204, 'siedlecki'),
    (322, 'Siemianowice Śląskie'),(268, 'siemiatycki'),(144, 'sieradzki'),(205, 'sierpecki'),(340, 'skarżyski'),(145, 'Skierniewice'),(146, 'skierniewicki'),(416, 'sławieński'),(119, 'słubicki'),
    (392, 'słupecki'),(288, 'Słupsk'),(289, 'słupski'),(206, 'sochaczewski'),(207, 'sokołowski'),(269, 'sokólski'),(290, 'Sopot'),(323, 'Sosnowiec'),(253, 'stalowowolski'),(341, 'starachowicki'),
    (417, 'stargardzki'),(291, 'starogardzki'),(342, 'staszowski'),(230, 'strzelecki'),(120, 'strzelecko-drezdenecki'),(17, 'strzeliński'),(254, 'strzyżowski'),(121, 'sulęciński'),
    (169, 'suski'),(270, 'suwalski'),(271, 'Suwałki'),(395, 'szamotulski'),(418, 'Szczecin'),(419, 'szczecinecki'),(363, 'szczycieński'),(292, 'sztumski'),(208, 'szydłowiecki'),(18, 'średzki'),
    (396, 'śremski'),(19, 'świdnicki'),(420, 'świdwiński'),(122, 'świebodziński'),(83, 'świecki'),(324, 'Świętochłowice'),(421, 'Świnoujście'),(255, 'Tarnobrzeg'),(256, 'tarnobrzeski'),
    (325, 'tarnogórski'),(170, 'tarnowski'),(171, 'Tarnów'),(172, 'tatrzański'),(293, 'tczewski'),(110, 'tomaszowski'),(84, 'Toruń'),(85, 'toruński'),(20, 'trzebnicki'),(86, 'tucholski');



INSERT INTO hv_county (id, name) VALUES (397, 'turecki'),(326, 'Tychy'),(173, 'wadowicki'),(21, 'wałbrzyski'),(422, 'wałecki'),(209, 'Warszawa'),(210, 'warszawski-zachodni'),(87, 'wąbrzeski'),
    (398, 'wągrowiecki'),(294, 'wejherowski'),(429, 'węgorzewski'),(211, 'węgrowski'),(174, 'wielicki'),(149, 'wieluński'),(150, 'wieruszowski'),(88, 'Włocławek'),(89, 'włocławski'),
    (111, 'włodawski'),(343, 'włoszczowski'),(327, 'wodzisławski'),(399, 'wolsztyński'),(212, 'wołomiński'),(22, 'wołowski'),(23, 'Wrocław'),(24, 'wrocławski'),(400, 'wrzesiński'),
    (123, 'wschowski'),(272, 'wysokomazowiecki'),(213, 'wyszkowski'),(328, 'Zabrze'),(273, 'zambrowski'),(112, 'zamojski'),(113, 'Zamość'),(329, 'zawierciański'),(25, 'ząbkowicki'),
    (151, 'zduńskowolski'),(152, 'zgierski'),(26, 'zgorzelecki'),(124, 'Zielona Góra'),(125, 'zielonogórski'),(27, 'złotoryjski'),(401, 'złotowski'),(214, 'zwoleński'),(126, 'żagański'),
    (127, 'żarski'),(90, 'żniński'),(330, 'Żory'),(215, 'żuromiński'),(216, 'żyrardowski'),(331, 'żywiecki');


INSERT INTO hv_borough (id, name) VALUES (1134, ' Żelechów'),(365, 'Abramów'),(402, 'Adamów'),(1472, 'Adamówka'),(1388, 'Albork'),(307, 'Aleksandrów'),(161, 'Aleksandrów Kujawski'),(774, 'Aleksandrów Łódzki'),
(801, 'Alwernia'),(605, 'Andrespol'),(1064, 'Andrychów'),(684, 'Andrzejewo'),(356, 'Annopol'),(1525, 'Augustów'),(1845, 'Babiak'),(803, 'Babice'),(537, 'Babimost'),(1107, 'Baborów'),
(793, 'Baboszewo'),(1960, 'Baćkowice'),(1707, 'Bakałarzewo'),(1400, 'Baligród'),(1971, 'Bałtów'),(2242, 'Banie'),(2110, 'Banie Mazurskie'),(661, 'Baranowo'),(425, 'Baranów'),(1518, 'Baranów Sandomierski'),
(2120, 'Barciany'),(284, 'Barcin'),(2171, 'Barczewo'),(143, 'Bardo'),(1529, 'Bargłów Koscielny'),(2277, 'Barlinek'),(173, 'Bartniczka'),(2421, 'Bartoszyce'),(273, 'Baruchowo'),(2309, 'Barwice'),
(340, 'Batorz'),(162, 'Bądkowo'),(574, 'Bedlno'),(1898, 'Bejsce'),(1145, 'Belsk Duży'),(563, 'Bełchatów'),(453, 'Bełżec'),(377, 'Bełżyce'),(1498, 'Besko'),(1566, 'Bestwina'),(720, 'Będków'),
(1553, 'Będzin'),(2264, 'Będzino'),(740, 'Biała'),(2427, 'Biała Piska'),(306, 'Biała Podlaska'),(685, 'Biała Rawska'),(611, 'Białaczów'),(178, 'Białe Błota'),(1091, 'Białobrzegi'),
(2192, 'Białogard'),(321, 'Białopole'),(1988, 'Białośliwie'),(1599, 'Białowieża'),(2310, 'Biały Bór'),(1051, 'Biały Dunajec'),(1572, 'Białystok'),(830, 'Biecz'),(959, 'Bielany'),
(6, 'Bielawa'),(596, 'Bielawy'),(2287, 'Bielice'),(1908, 'Bieliny'),(745, 'Bielsk'),(2382, 'Bielsk Podlaski'),(1576, 'Bielski Podlaski'),(1586, 'Bielsko-Biała'),(1115, 'Bierawa'),
(1588, 'Bieruń'),(79, 'Bierutów'),(2197, 'Bierzwnik'),(2265, 'Biesiekierz'),(1070, 'Bieżuń'),(308, 'Biłgoraj'),(1462, 'Bircza'),(1082, 'Biskupice'),(2153, 'Biskupiec'),(309, 'Biszcza'),
(2053, 'Bisztynek'),(1624, 'Blachownia'),(501, 'Bledzew'),(1809, 'Blizanów'),(2013, 'Bliżyn'),(694, 'Błaszki'),(1484, 'Błażowa'),(1146, 'Błędów'),(993, 'Błonie'),(2266, 'Bobolice'),
(832, 'Bobowa'),(1439, 'Bobowo'),(495, 'Bobrowice'),(211, 'Bobrowniki'),(169, 'Bobrowo'),(780, 'Bochnia'),(1578, 'Boćki'),(747, 'Bodzanów'),(1973, 'Bodzechów'),(1909, 'Bodzentyn');



INSERT INTO hv_borough (id, name) VALUES (150, 'Bogatynia'),(487, 'Bogdaniec'),(2031, 'Bogoria'),(2376, 'Boguchwała'),(118, 'Boguszów-Gorce'),(686, 'Boguty-Pianki'),(538, 'Bojadła'),(2067, 'Bojanowo'),
(1505, 'Bojanów'),(1590, 'Bojszowy'),(2377, 'Bokowsko'),(816, 'Bolesław'),(1, 'Bolesławiec'),(2279, 'Boleszkowice'),(707, 'Bolimów'),(21, 'Bolków'),(274, 'Boniewo'),
(1772, 'Borek Wielkopolski'),(435, 'Borki'),(840, 'Borkowice'),(2311, 'Borne Sulinowo'),(1694, 'Boronów'),(1434, 'Borowa'),(1119, 'Borowie'),(95, 'Borów'),(378, 'Borzechów'),
(789, 'Borzęcin'),(1303, 'Borzytuchom'),(1833, 'Bralin'),(1108, 'Branice'),(2059, 'Braniewo'),(1580, 'Brańsk'),(1049, 'Brańszczyk'),(2051, 'Bratoszyce'),(696, 'Brąszewice'),
(1600, 'Brenna'),(948, 'Brochów'),(170, 'Brodnica'),(554, 'Brody'),(2233, 'Brojce'),(688, 'Brok'),(606, 'Brójce'),(751, 'Brudzeń Duży'),(2135, 'Brudzew'),(1320, 'Brusy'),(815, 'Brwinów'),
(1096, 'Brzeg'),(128, 'Brzeg Dolny'),(792, 'Brzesko'),(980, 'Brzeszcze'),(275, 'Brześć Kujawski'),(570, 'Brzeziny'),(546, 'Brzeźnica'),(2347, 'Brzeźnio'),(697, 'Brzeźno'),(1294, 'Brzostek'),
(171, 'Brzozie'),(1279, 'Brzozów'),(234, 'Brzuze'),(1335, 'Brzyska'),(584, 'Buczek'),(1568, 'Buczkowice'),(2429, 'Budry'),(721, 'Budziszewice'),(1004, 'Budzów'),(1736, 'Budzyń'),
(1485, 'Buguchwała'),(2022, 'Buk'),(242, 'Bukowiec'),(1053, 'Bukowina Tatrzańska'),(1499, 'Bukowisko'),(972, 'Bukowno'),(2378, 'Bukowsko'),(753, 'Bulkowo'),(2234, 'Burdy'),(699, 'Burzenin'),
(1865, 'Busko-Zdrój'),(379, 'Bychawa'),(1157, 'Byczyna'),(2332, 'Bydgoszcz'),(1005, 'Bystra-Sidzina'),(40, 'Bystrzyca Kłodzka'),(496, 'Bytnica'),(1597, 'Bytom'),(507, 'Bytom Odrzański'),
(228, 'Bytoń'),(1304, 'Bytów'),(1333, 'Cedry Wielkie'),(2243, 'Cedynia'),(1223, 'Cegłów'),(262, 'Cekcyn'),(1813, 'Ceków-Kolonia'),(715, 'Celestynów'),(961, 'Ceranów'),(1380, 'Cewice'),
(890, 'Charsznica'),(597, 'Chąśno'),(320, 'Chełm'),(1592, 'Chełm śląski'),(981, 'Chełmek'),(917, 'Chełmiec'),(186, 'Chełmno'),(254, 'Chełmża'),(1911, 'Chęciny'),(978, 'Chlewiska'),
(1306, 'Chłopice'),(1486, 'Chmielnik'),(1347, 'Chmielno'),(276, 'Choceń'),(89, 'Chocianów'),(2297, 'Chociwel'),(2007, 'Chocz'),(1540, 'Choczewo'),(277, 'Chodecz'),(411, 'Chodel');



INSERT INTO hv_borough (id, name) VALUES (1848, 'Chodów'),(1737, 'Chodzież'),(2244, 'Chojna'),(1321, 'Chojnice'),(53, 'Chojnów'),(1373, 'Chorkówka'),(1536, 'Choroszcz'),(824, 'Chorzele'),
(1598, 'Chorzów'),(2198, 'Choszczno'),(1183, 'Chotcza'),(212, 'Chrostkowo'),(341, 'Chrzanów'),(2369, 'Chrząstowice'),(1937, 'Chrzypsko Wielkie'),(1601, 'Chybie'),(1147, 'Chynów'),
(1695, 'Ciasna'),(1281, 'Ciechanowiec'),(1102, 'Ciechanów'),(192, 'Ciechocin'),(2331, 'Ciechocinek'),(163, 'Ciecocinek'),(687, 'Cielądz'),(1185, 'Ciepielów'),(144, 'Ciepłowody'),
(1411, 'Cieszanów'),(76, 'Cieszków'),(1603, 'Cieszyn'),(1020, 'Ciężkowice'),(1116, 'Cisek'),(1402, 'Cisna'),(1357, 'Cmolas'),(514, 'Cybinka'),(394, 'Cyców'),(1979, 'Czajków'),
(2209, 'Czaplinek'),(1273, 'Czarna'),(1539, 'Czarna Białostocka'),(1307, 'Czarna Dąbrówka'),(1440, 'Czarna Woda'),(1324, 'Czarne'),(662, 'Czarnia'),(1742, 'Czarnków'),
(634, 'Czarnocin'),(742, 'Czarnożyły'),(119, 'Czarny Bór'),(942, 'Czarny Dunajec'),(760, 'Czastary'),(794, 'Czchów'),(1571, 'Czechowice-Dziedzice'),(1556, 'Czeladź'),(436, 'Czemierniki'),
(1899, 'Czempiń'),(1840, 'Czeniców'),(1602, 'Czeremcha'),(1436, 'Czermin'),(133, 'Czernica'),(826, 'Czernice Borowe'),(2352, 'Czernichów'),(1756, 'Czerniejewo'),(2348, 'Czerniewice'),
(255, 'Czernikowo'),(1322, 'Czersk'),(539, 'Czerwieńsk'),(663, 'Czerwin'),(796, 'Czerwińsk Nad Wisłą'),(1757, 'Czerwionka-Leszczyny'),(2025, 'Czerwonak'),(1205, 'Czerwonka'),
(1622, 'Częstochowa'),(2321, 'Człopa'),(1325, 'Człuchów'),(943, 'Czorsztyn'),(1262, 'Czosnów'),(1218, 'Czrząstowice'),(1511, 'Czudec'),(1604, 'Czyże'),(1282, 'Czyżew-Osada'),
(1978, 'Ćmielów'),(1913, 'Daleszyce'),(646, 'Dalików'),(2151, 'Damasławek'),(1422, 'Damnica'),(2293, 'Darłowo'),(589, 'Daszyna'),(497, 'Dąbie'),(219, 'Dąbrowa'),(1685, 'Dąbrowa Białostocka'),
(203, 'Dąbrowa Biskupia'),(179, 'Dąbrowa Chełmińska'),(1650, 'Dąbrowa Górnicza'),(818, 'Dąbrowa Tarnowska'),(1626, 'Dąbrowa Zielona'),(575, 'Dąbrowice'),(1028, 'Dąbrówka'),(2199, 'Dąbrówno'),
(1326, 'Debrzno'),(488, 'Deszczno'),(1226, 'Dębe Wielkie'),(1297, 'Dębica'),(442, 'Dęblin'),(1424, 'Dębnica Kaszubska'),(795, 'Dębno'),(418, 'Dębowa Kłoda'),(268, 'Dębowa Łąka'),(1337, 'Dębowiec'),
(134, 'Długołęka'),(1052, 'Długosiodło'),(619, 'Dłutów'),(571, 'Dmosin'),(2355, 'Dobczyce'),(519, 'Dobiegniew'),(875, 'Dobra'),(2283, 'Dobra /Szczecińska/'),(180, 'Dobrcz'),(229, 'Dobre'),
(2176, 'Dobre Miasto'),(1201, 'Dobrodzień'),(105, 'Dobromierz'),(620, 'Dobroń'),(80, 'Dobroszyce'),(2298, 'Dobrzany'),(1222, 'Dobrzeń Wielki'),(2012, 'Dobrzyca'),(902, 'Dobrzyce'),
(1541, 'Dobrzyniewo Duże'),(213, 'Dobrzyń Nad Wisłą'),(2299, 'Dolice'),(2127, 'Dolsk'),(332, 'Dołhobyczów'),(906, 'Domanice'),(598, 'Domaniewice'),(86, 'Domaniów'),(1283, 'Domaradz');



INSERT INTO hv_borough (id, name) VALUES (1171, 'Domaszowice'),(2109, 'Dominowo'),(2027, 'Dopiewo'),(322, 'Dorohusk'),(1980, 'Doruchów'),(243, 'Dragacz'),(2200, 'Drawno'),(1744, 'Drawsko'),
(2212, 'Drawsko Pomorskie'),(290, 'Drelów'),(520, 'Drezdenko'),(755, 'Drobin'),(1668, 'Drohiczyn'),(564, 'Drużbice'),(781, 'Drwinia'),(612, 'Drzewica'),(244, 'Drzycim'),(2424, 'Dubeninki'),
(1607, 'Dubicze Cerkiewne'),(1463, 'Dubiecko'),(2335, 'Dubienka'),(1375, 'Dukla'),(2092, 'Duszniki'),(41, 'Duszniki-Zdrój'),(1995, 'Dwikozy'),(1284, 'Dydnia'),(2257, 'Dygowo'),
(1487, 'Dynów'),(2178, 'Dywity'),(81, 'Dziadkowa Kłoda'),(1669, 'Dziadkowice'),(2327, 'Dziadowa Kłoda'),(2423, 'Działdowo'),(1991, 'Działoszyce'),(625, 'Działoszyn'),(1361, 'Dziemiany'),
(800, 'Dzierzążnia'),(1522, 'Dzierzgoń'),(1246, 'Dzierzgowo'),(2338, 'Dzierzkowice'),(357, 'Dzierzykowice'),(7, 'Dzierżoniów'),(1360, 'Dzikowiec'),(2251, 'Dziwnów'),(342, 'Dzwola'),(2215, 'Dźwierzuty'),
(2077, 'Elbląg'),(2095, 'Ełk'),(278, 'Fabianki'),(347, 'Fajsławice'),(1947, 'Fałków'),(1267, 'Filipów'),(366, 'Firlej'),(310, 'Frampol'),(1464, 'Fredropol'),(2060, 'Frombork'),
(1512, 'Frysztak'),(2372, 'Gać'),(1473, 'Gać Jawornik'),(762, 'Galewice'),(1155, 'Garbatka-Letnisko'),(380, 'Garbów'),(1372, 'Gardeja'),(1121, 'Garwolin'),(1760, 'Gaszowice'),(1442, 'Gawłuszowice'),
(90, 'Gaworzyce'),(758, 'Gąbin'),(285, 'Gąsawa'),(1332, 'Gdańsk'),(1085, 'Gdów'),(1346, 'Gdynia'),(1661, 'Giby'),(659, 'Gidle'),(842, 'Gielniów'),(1654, 'Gierałtowice'),(2180, 'Gietrzwałd'),
(1842, 'Gilowice'),(2015, 'Gizałki'),(2102, 'Giżycko'),(1105, 'Glinojeck'),(1652, 'Gliwice'),(12, 'Głogów'),(1488, 'Głogów Małopolski'),(1247, 'Głogówek'),(1156, 'Głowaczów'),(775, 'Głowno'),
(1427, 'Główczyce'),(1110, 'Głubczyce'),(1179, 'Głuchołazy'),(708, 'Głuchów'),(381, 'Głusk'),(120, 'Głuszyca'),(1528, 'Gniew'),(1542, 'Gniewino'),(204, 'Gniewkowo'),(1159, 'Gniewoszów'),
(1758, 'Gniezno'),(797, 'Gnojnik'),(1867, 'Gnojno'),(1730, 'Goczałkowice-Zdrój'),(2079, 'Godkowo'),(1797, 'Godów'),(709, 'Godzianów'),(1816, 'Godziesze Wielkie'),(343, 'Godziszów'),
(1162, 'Gogolin'),(2252, 'Golczewo'),(2220, 'Goleniów'),(2401, 'Goleszów'),(1870, 'Golina'),(193, 'Golub-Dobrzyń'),(2154, 'Gołańcz'),(892, 'Gołcza'),(2111, 'Gołdap'),(2016, 'Gołuchów'),
(1109, 'Gołymin-Ośrodek'),(665, 'Gomunice'),(1647, 'Goniądz'),(311, 'Goraj'),(833, 'Gorlice'),(635, 'Gorzkowice'),(348, 'Gorzków'),(1204, 'Gorzów Śląski'),(494, 'Gorzów Wielkopolski'),
(1520, 'Gorzyce'),(263, 'Gostycyn'),(1135, 'Gostynin'),(1774, 'Gostyń'),(700, 'Goszczanów'),(1148, 'Goszczyn'),(358, 'Gościeradów'),(2258, 'Gościno'),(1948, 'Gowarczów'),(664, 'Goworowo'),
(547, 'Gozdnica'),(928, 'Gozdowo'),(17, 'Góra'),(736, 'Góra Kalwaria'),(590, 'Góra Świętej Małgorzaty'),(1915, 'Górno'),(2055, 'Górowo Iławieckie'),(172, 'Górzno'),(515, 'Górzyca'),
(874, 'Gózd'),(636, 'Grabica'),(473, 'Grabowiec'),(1615, 'Grabowo'),(591, 'Grabów'),(1164, 'Grabów Nad Pilicą'),(1981, 'Grabów Nad Prosną'),(1587, 'Grajewo'),(1787, 'Granowo'),
(1009, 'Grębków'),(91, 'Grębocice'),(820, 'Gręboszów'),(1521, 'Grębów'),(1098, 'Grodków'),(2155, 'Grodziczno'),(1871, 'Grodziec'),(1671, 'Grodzisk'),(1140, 'Grodzisk Mazowiecki'),
(1790, 'Grodzisk Wielkopolski'),(1407, 'Grodzisko Dolne'),(2, 'Gromadka'),(1022, 'Gromnik'),(2082, 'Gronowo Elbląskie'),(1543, 'Gródek'),(919, 'Gródek Nad Dunajcem');



INSERT INTO hv_borough (id, name) VALUES (1149, 'Grójec'),(1111, 'Grudusk'),(197, 'Grudziądz'),(2426, 'Grunwald'),(198, 'Gruta'),(2357, 'Grybów'),(2235, 'Gryfice'),(2245, 'Gryfino'),
(71, 'Gryfów Śląski'),(1852, 'Grzegorzew'),(2312, 'Grzmiąca'),(498, 'Gubin'),(858, 'Gzy'),(1286, 'Haczów'),(1608, 'Hajnówka'),(1230, 'Halinów'),(465, 'Hanna'),(466, 'Hańsk'),
(1455, 'Harasiuki'),(1606, 'Hażlach'),(1401, 'Hel'),(1697, 'Herby'),(333, 'Horodło'),(1412, 'Horyniec-Zdrój'),(334, 'Hrubieszów'),(1194, 'Huszlew'),(1489, 'Hyżne'),(1387, 'Ichnowy'),
(844, 'Igołomia-Wawrzeńczyce'),(2112, 'Iława'),(548, 'Iłowa'),(2068, 'Iłowo-Osada'),(949, 'Iłów'),(877, 'Iłża'),(1594, 'Imielin'),(1880, 'Imielno'),(724, 'Inowłódx'),
(2349, 'Inowłódź'),(205, 'Inowrocław'),(2300, 'Ińsko'),(1815, 'Irządze'),(1609, 'Istebna'),(1962, 'Iwaniska'),(845, 'Iwanowice'),(1479, 'Iwierzyce'),(798, 'Iwkowa'),
(1378, 'Iwonicz-Zdrój'),(995, 'Izabelin'),(349, 'Izbica'),(279, 'Izbica Kujawska'),(1251, 'Izbicko'),(944, 'Jabłonka'),(382, 'Jabłonna'),(963, 'Jabłonna Lacka'),(174, 'Jabłonowo Pomorskie'),
(419, 'Jabłoń'),(1030, 'Jadów'),(1141, 'Jaktorów'),(1232, 'Jakubów'),(1269, 'Jaleniewo'),(206, 'Janikowo'),(28, 'Janowice Wielkie'),(426, 'Janowiec'),(2148, 'Janowiec Kościelny'),
(286, 'Janowiec Wielkopolski'),(2149, 'Janowo'),(1628, 'Janów'),(344, 'Janów Lubelski'),(291, 'Janów Podlaski'),(1800, 'Jaraczewo'),(454, 'Jarczów'),(1456, 'Jarocin'),(1309, 'Jarosław'),
(1573, 'Jasienica'),(2370, 'Jasienica Rosielna'),(1150, 'Jasieniec'),(1289, 'Jasiennica Rosielna'),(555, 'Jasień'),(1649, 'Jasionówka'),(1339, 'Jasło'),(2395, 'Jastarnia'),(383, 'Jastków'),
(2179, 'Jastrowie'),(982, 'Jastrząb'),(880, 'Jastrzębia'),(1672, 'Jastrzębie-Zdrój'),(1651, 'Jaświły'),(22, 'Jawor'),(2373, 'Jawornik Polski'),(1574, 'Jaworze'),(1674, 'Jaworzno'),
(106, 'Jaworzyna Śląska'),(1382, 'Jedlicze'),(122, 'Jedlina-Zdrój'),(882, 'Jedlińsk'),(886, 'Jedlnia-Letnisko'),(829, 'Jednorożec'),(1627, 'Jedwabne'),(2218, 'Jedwabno'),(1762, 'Jejkowice'),
(87, 'Jelcz-Laskowice'),(27, 'Jelenia Góra'),(1711, 'Jeleniewo'),(2384, 'Jeleniowo'),(1844, 'Jeleśnia'),(1252, 'Jemielnica'),(18, 'Jemielno'),(846, 'Jerzanowice-Przeginia'),(13, 'Jerzmanowa'),
(2353, 'Jerzmanowice-Przeginia'),(2344, 'Jesień'),(220, 'Jeziora Wielkie'),(2182, 'Jeziorany'),(367, 'Jeziorzany'),(245, 'Jeżewo'),(1457, 'Jeżowe'),(572, 'Jeżów'),(29, 'Jeżów Sudecki'),
(1882, 'Jędrzejów'),(1300, 'Jodłowa'),(876, 'Jodłowniki'),(802, 'Joniec'),(2185, 'Jonkowo'),(1007, 'Jordanów'),(135, 'Jordanów Śląski'),(312, 'Józefów'),(412, 'Józefów Nad Wisłą');



INSERT INTO hv_borough (id, name) VALUES (1404, 'Jstarnia'),(1547, 'Juchnowiec Kościelny'),(2070, 'Jutrosin'),(1990, 'Kaczory'),(666, 'Kadzidło'),(1777, 'Kalety'),(2097, 'Kalinowo'),
(1441, 'Kaliska'),(1829, 'Kalisz'),(2214, 'Kalisz Pomorski'),(1069, 'Kalwaria Zebrzydowska'),(1233, 'Kałuszyn'),(2354, 'Kamienica'),(1630, 'Kamienica Polska'),(1793, 'Kamieniec'),
(145, 'Kamieniec Ząbkowicki'),(37, 'Kamienna Góra'),(1181, 'Kamiennik'),(323, 'Kamień'),(239, 'Kamień Krajeński'),(2253, 'Kamień Pomorski'),(667, 'Kamieńsk'),(368, 'Kamionka'),(929, 'Kamionka Wielka'),
(998, 'Kampinos'),(2374, 'Kańczuga'),(730, 'Karczew'),(413, 'Karczmiska'),(540, 'Kargowa'),(2193, 'Karlino'),(2237, 'Karnice'),(1208, 'Karniewo'),(30, 'Karpacz'),(2389, 'Karsin'),(1363, 'Karsni'),
(1349, 'Kartuzy'),(1676, 'Katowice'),(2139, 'Kawęczyn'),(1061, 'Kazanów'),(1874, 'Kazimierz Biskupi'),(427, 'Kazimierz Dolny'),(1903, 'Kazimierza Wielka'),(2094, 'Kaźmierz'),(437, 'Kąkolewnica Wschodnia'),
(136, 'Kąty Wrocławskie'),(223, 'Kcyna'),(2333, 'Kcynia'),(1120, 'Kędzierzyn-Koźle'),(1428, 'Kępice'),(1835, 'Kępno'),(264, 'Kęsowo'),(2121, 'Kętrzyn'),(983, 'Kęty'),(2414, 'Kielce'),
(626, 'Kiełczygów'),(599, 'Kiernozia'),(1113, 'Kietrz'),(1992, 'Kije'),(187, 'Kijewo Królewskie'),(214, 'Kikół'),(2114, 'Kisielice'),(1759, 'Kiszkowo'),(2128, 'Kiwity'),(1877, 'Kleczew'),
(1031, 'Klembów'),(1610, 'Kleszczele'),(2435, 'Kleszczewo'),(2029, 'Kleszczowo'),(565, 'Kleszczów'),(1996, 'Klimontów'),(701, 'Klonowa'),(1158, 'Kluczbork'),(974, 'Klucze'),
(2042, 'Kluczewsko'),(566, 'Kluki'),(1285, 'Klukowo'),(843, 'Klwów'),(1086, 'Kłaj'),(1761, 'Kłecko'),(1678, 'Kłobuck'),(443, 'Kłoczew'),(489, 'Kłodawa'),(42, 'Kłodzko'),(1631, 'Kłomnice'),
(1934, 'Kłoszakowice'),(1655, 'Knurów'),(1653, 'Knyszyn'),(670, 'Kobiele Wielkie'),(137, 'Kobierzyce'),(1731, 'Kobiór'),(1982, 'Kobyla Góra'),(2301, 'Kobylanka'),(1907, 'Kobylin'),(1287, 'Kobylin-Borzymy'),
(1431, 'Kobylnica'),(1032, 'Kobyłka'),(1699, 'Kochanowice'),(600, 'Kocierzew Południowy'),(369, 'Kock'),(848, 'Kocmyrzów-Luborzyca'),(1328, 'Koczała'),(292, 'Kodeń'),(672, 'Kodrąb'),(1336, 'Kolbudy'),
(1362, 'Kolbuszowa'),(1617, 'Kolno'),(1264, 'Kolonowskie'),(508, 'Kolsko'),(607, 'Koluszki'),(2169, 'Kołaczkowo'),(1341, 'Kołaczyce'),(2386, 'Kołaki Kościelne'),(2284, 'Kołbaskowo'),
(722, 'Kołbiel'),(2388, 'Kołczygłowy'),(1856, 'Koło'),(2259, 'Kołobrzeg'),(1308, 'Kołygłowy'),(1500, 'Komańcza'),(474, 'Komarów-Osada'),(438, 'Komarówka Podlaska'),(2032, 'Komorniki'),
(1224, 'Komprachcice'),(1323, 'Konarzyny'),(96, 'Kondratowice'),(164, 'Koneck'),(1634, 'Koniecpol'),(1868, 'Konin'),(994, 'Konisza'),(2360, 'Koniusza'),(1635, 'Konopiska'),(384, 'Konopnica');



INSERT INTO hv_borough (id, name) VALUES (737, 'Konstancin-Jeziorna'),(293, 'Konstantynów'),(621, 'Konstantynów Łódzki'),(1949, 'Końskie'),(428, 'Końskowola'),(1997, 'Koprzywnica'),(908, 'Korczew'),
(1386, 'Korczyna'),(1184, 'Korfantów'),(1741, 'Kornowac'),(181, 'Koronowo'),(2122, 'Korsze'),(1689, 'Korycin'),(1011, 'Korytnica'),(2358, 'Korzenna'),(1413, 'Kosakowo'),(965, 'Kosów Lacki'),
(100, 'Kostomłoty'),(2036, 'Kostrzyn'),(490, 'Kostrzyn Nad Odrą'),(2263, 'Koszalin'),(1846, 'Koszarawa'),(1701, 'Koszęcin'),(996, 'Koszyce'),(1901, 'Kościan'),(1860, 'Kościelec'),(1055, 'Kościelisko'),
(1364, 'Kościerzyna'),(14, 'Kotla'),(1803, 'Kotlin'),(910, 'Kotuń'),(280, 'Kowal'),(887, 'Kowala'),(2162, 'Kowale Oleckie'),(194, 'Kowalewo Pomorskie'),(31, 'Kowary'),(710, 'Kowiesy'),(1720, 'Koziegłowy'),
(2288, 'Kozielice'),(1166, 'Kozienice'),(2150, 'Kozłowo'),(893, 'Kozłów'),(2400, 'Kozy'),(1910, 'Koźmin Wielkopolski'),(1817, 'Koźminek'),(509, 'Kożuchów'),(2038, 'Kórnik'),(2181, 'Krajenka'),(873, 'Kraków'),
(1879, 'Kramsk'),(1163, 'Krapkowice'),(1465, 'Krasiczyn'),(831, 'Krasne'),(475, 'Krasnobród'),(1662, 'Krasnopol'),(1210, 'Krasnosielc'),(350, 'Krasnystaw'),(2044, 'Krasocin'),(1983, 'Kraszewice'),(351, 'Kraśniczyn'),
(359, 'Kraśnik'),(1344, 'Krempna'),(1776, 'Krobia'),(1819, 'Kroczyce'),(1415, 'Krokowa'),(1371, 'Krosno'),(499, 'Krosno Odrzańskie'),(950, 'Krościenko Nad Dunajcem'),(1389, 'Krościenko Wyżne'),(77, 'Krośnice'),
(576, 'Krośniewice'),(54, 'Krotoszyce'),(1914, 'Krotoszyn'),(2104, 'Kruklanki'),(1780, 'Krupski Młyn'),(207, 'Kruszwica'),(1637, 'Kruszyna'),(1393, 'Krynica Morska'),(931, 'Krynica-Zdrój'),(455, 'Krynice'),
(1691, 'Krynki'),(1656, 'Krypno'),(1743, 'Krzanowice'),(385, 'Krzczonów'),(1925, 'Krzemieniewo'),(1679, 'Krzepice'),(850, 'Krzeszowice'),(1458, 'Krzeszów'),(523, 'Krzeszyce'),(2202, 'Krzęcin'),
(2113, 'Krzykosy'),(1881, 'Krzymów'),(835, 'Krzynowłoga Mała'),(1466, 'Krzywcza'),(403, 'Krzywda'),(1902, 'Krzywiń'),(1746, 'Krzyż Wielkopolski'),(2408, 'Krzyżanowice'),(577, 'Krzyżanów'),(622, 'Ksawerów'),
(895, 'Książ Wielki'),(2130, 'Książ Wielkopolski'),(269, 'Książki'),(313, 'Księżpol'),(1073, 'Kuczbork-Osada'),(43, 'Kudowa-Zdrój'),(1288, 'Kulesze Kościelne'),(55, 'Kunice'),(1984, 'Kunów'),
(429, 'Kurów'),(1408, 'Kuryłówka'),(2158, 'Kurzętnik'),(1945, 'Kuślin'),(578, 'Kutno'),(1745, 'Kuźnia Raciborska'),(1693, 'Kuźnica'),(1374, 'Kwidzyn'),(1939, 'Kwilcz'),(1071, 'Lanckorona'),
(878, 'Laskowa'),(1160, 'Lasowice Wielkie'),(1311, 'Laszki'),(1235, 'Latowicz'),(2078, 'Lądek'),(44, 'Lądek-Zdrój'),(1175, 'Legionowo'),(52, 'Legnica'),(2326, 'Legnickie Pole'),(56, 'Legnickie-Pole'),
(668, 'Lelis'),(2061, 'Lelkowo'),(1638, 'Lelów'),(649, 'Leoncin'),(1403, 'Lesko'),(1000, 'Leszno'),(732, 'Lesznowola'),(61, 'Leśna'),(294, 'Leśna Podlaska'),(1265, 'Leśnica'),(324, 'Leśniowice'),(1100, 'Lewin Brzeski'),
(45, 'Lewin Kłodzki'),(1409, 'Leżajsk'),(1381, 'Lębork'),(1596, 'Lędziny'),(674, 'Lgota Wielka'),(810, 'Libiąż'),(2392, 'Lichnowy'),(2069, 'Lidzbark'),(2129, 'Lidzbark Warmiński'),(879, 'Limanowa'),
(1544, 'Linia'),(1366, 'Liniewo'),(711, 'Lipce Reymontowskie'),(2289, 'Lipiany'),(1681, 'Lipie'),(2350, 'Lipinki'),(556, 'Lipinki Łużyckie'),(2183, 'Lipka'),(1310, 'Lipnica'),(782, 'Lipnica Murowana'),
(952, 'Lipnica Wielka'),(1963, 'Lipnik'),(834, 'Lipniki'),(215, 'Lipno'),(1847, 'Lipowa'),(1253, 'Lipowiec Kościelnu'),(2365, 'Lipowiec Kościelny'),(1531, 'Lipsk'),(1186, 'Lipsko'),(1368, 'Lipusz'),
(188, 'Lisewo'),(1024, 'Lisia Góra'),(1818, 'Lisków'),(852, 'Liszki'),(1012, 'Liw'),(246, 'Lniano'),(1414, 'Lubaczów'),(281, 'Lubanie'),(63, 'Lubań'),(370, 'Lubartów'),(1748, 'Lubasz'),(2115, 'Lubawa'),(38, 'Lubawka'),(1492, 'Lubenia'),
(1443, 'Lubichowo'),(256, 'Lubicz'),(904, 'Lubień'),(282, 'Lubień Kujawski'),(265, 'Lubiewo'),(68, 'Lubin'),(491, 'Lubiszyn'),(393, 'Lublin'),(1703, 'Lubliniec'),(524, 'Lubniewice'),
(725, 'Lubochnia'),(1802, 'Lubomia'),(72, 'Lubomierz'),(2131, 'Lubomino'),(2041, 'Luboń'),(1075, 'Lubowidz'),(283, 'Lubraniec'),(528, 'Lubrza'),(557, 'Lubsko'),(1103, 'Lubsza'),(456, 'Lubycza Królewska'),
(395, 'Ludwin'),(1077, 'Lutocin'),(623, 'Lutomiersk'),(1274, 'Lutowiska'),(764, 'Lututów'),(1545, 'Luzino'),(1946, 'Lwówek'),(73, 'Lwówek Śląski'),(1764, 'Lyski'),(287, 'Łabiszyn'),(933, 'Łabowa'),
(476, 'Łabunie'),(676, 'Ładzice'),(8, 'Łagiewniki'),(529, 'Łagów'),(1187, 'Łambinowice'),(579, 'Łanięta'),(1429, 'Łańcut'),(783, 'Łapanów'),(2359, 'Łapsze Niżne'),(1549, 'Łapy'),(199, 'Łasin'),(585, 'Łask'),
(1124, 'Łaskarzew'),(457, 'Łaszczów'),(414, 'Łaziska'),(1708, 'Łaziska Górne'),(1820, 'Łazy'),(759, 'Łąck'),(934, 'Łącko'),(2014, 'Łączna'),(1383, 'Łeba'),(398, 'Łęczna'),(592, 'Łęczyca'),(2398, 'Łęczyce'),
(1838, 'Łęka Opatowska'),(2411, 'Łękawica'),(1849, 'Łękawice'),(637, 'Łęki Szlacheckie'),(558, 'Łęknica'),(2273, 'Łobez'),(1994, 'Łobżenica'),(1014, 'Łochów'),(2412, 'Łodygowice'),(295, 'Łomazy'),(1003, 'Łomianki'),
(1625, 'Łomża'),(2001, 'Łoniów'),(352, 'Łopiennik Górny'),(1918, 'Łopuszno'),(1198, 'Łosice'),(935, 'Łososina Dolna'),(601, 'Łowicz'),(610, 'Łódź'),(257, 'Łubianka'),(1225, 'Łubniany'),(766, 'Łubnice'),
(1763, 'Łubowo'),(314, 'Łukowa'),(881, 'Łukowica'),(404, 'Łuków'),(2201, 'Łukta'),(836, 'Łużna'),(669, 'Łyse'),(258, 'Łysomice'),(602, 'Łyszkowice'),(1126, 'Maciejowice'),(1169, 'Magnuszew'),(1365, 'Majdan Królewski'),
(716, 'Maków'),(1212, 'Maków Mazowiecki'),(1010, 'Maków Podhalański'),(2141, 'Malanów'),(2393, 'Malbork'),(101, 'Malczyce'),(2294, 'Malechowo'),(761, 'Mała Wieś'),(2203, 'Małdyty'),(448, 'Małgiew'),
(690, 'Małkinia Górna'),(1884, 'Małogoszcz'),(549, 'Małomice'),(1620, 'Mały Płock'),(2267, 'Manowo'),(107, 'Marcinkowice'),(2330, 'Marcinowice'),(39, 'Marciszów'),(1739, 'Margonin'),(2302, 'Marianowo'),
(1035, 'Marki'),(1804, 'Marklowice'),(1430, 'Markowa'),(2084, 'Markusy'),(430, 'Markuszów'),(1919, 'Masłow'),(677, 'Masłowice'),(2415, 'Masłów'),(500, 'Maszewo'),(1467, 'Medyka'),(2339, 'Mełgiew'),(23, 'Męcinka'),
(823, 'Mędrzechów'),(82, 'Mędzybórz'),(1998, 'Miasteczko Krajeńskie'),(1782, 'Miasteczko Śląskie'),(1312, 'Miastko'),(1629, 'Miastkowo'),(1128, 'Miastków Kościelny'),(477, 'Miączyn'),(853, 'Michałkowice'),
(817, 'Michałowice'),(1552, 'Michałowo'),(2418, 'Michałów'),(371, 'Michów'),(897, 'Miechów'),(1921, 'Miedziana Góra'),(1950, 'Miedzichowo'),(1017, 'Miedzna'),(1732, 'Miedźna'),(1683, 'Miedźno'),
(1392, 'Miejsce Piastowe'),(2073, 'Miejska Górka'),(1765, 'Mielaszyn'),(1444, 'Mielec'),(2430, 'Mieleszyn'),(1673, 'Mielnik'),(2268, 'Mielno'),(123, 'Mieroszów'),(1558, 'Mierzęcice'),(2246, 'Mieszkowice'),
(2156, 'Mieścicsko'),(2436, 'Mieścisko'),(138, 'Mietków'),(2328, 'Międzybórz'),(1942, 'Międzychód'),(46, 'Międzylesie'),(296, 'Międzyrzec Podlaski'),(502, 'Międzyrzecz'),(2254, 'Międzyzdroje'),(102, 'Miękina'),
(2134, 'Mikołajki'),(1524, 'Mikołajki Pomorskie'),(1710, 'Mikołów'),(1985, 'Mikstat'),(420, 'Milanów'),(1142, 'Milanówek'),(1675, 'Milejczyce'),(2087, 'Milejewo'),(399, 'Milejów'),(78, 'Milicz'),
(1850, 'Milówka'),(2204, 'Miłakowo'),(2106, 'Miłki'),(57, 'Miłkowice'),(2207, 'Miłomłyn'),(1390, 'Miłoradz'),(2172, 'Miłosław'),(1237, 'Mińsk Mazowiecki'),(336, 'Mircze'),(2322, 'Mirosławiec'),
(984, 'Mirów'),(74, 'Mirsk'),(2024, 'Mirzec'),(951, 'Mlodzieszyn'),(1254, 'Mława'),(2368, 'Młodzieszyn'),(2088, 'Młynary'),(1213, 'Młynarze'),(1923, 'Mniów'),(613, 'Mniszków'),(930, 'Mochowo'),
(345, 'Modliborzyce'),(1151, 'Mogielnica'),(855, 'Mogilany'),(221, 'Mogilno'),(913, 'Mokobody'),(746, 'Mokrsko'),(1657, 'Mońki'),(1924, 'Morawic'),(2416, 'Morawica'),(2208, 'Morąg'),(914, 'Mordy'),
(2247, 'Moryń'),(1530, 'Morzeszczyn'),(2043, 'Mosina'),(2046, 'Moskorzew'),(638, 'Moszczenica'),(2137, 'Mrągowo'),(224, 'Mrocza'),(1239, 'Mrozy'),(1641, 'Mstów'),(1806, 'Mszana'),(883, 'Mszana Dolna'),
(1084, 'Mszczonów'),(24, 'Mściwojów'),(1072, 'Mucharz'),(2045, 'Murowana Goślina'),(1227, 'Murów'),(936, 'Muszyna'),(1821, 'Mycielin'),(1643, 'Mykanów'),(32, 'Mysłakowice'),(1719, 'Mysłowice'),(2407, 'Myszków'),
(671, 'Myszyniec'),(905, 'Myślenice'),(2281, 'Myślibórz'),(819, 'Nadarzyn'),(1885, 'Nagłowice'),(225, 'Nakło Nad Notecą'),(431, 'Nałęczów'),(1173, 'Namysłów'),(1612, 'Narew'),(1613, 'Narewka'),
(1416, 'Narol'),(804, 'Naruszewo'),(653, 'Nasielsk'),(937, 'Nawojowa'),(2173, 'Nekla'),(1747, 'Nędza'),(2152, 'Nidzica'),(603, 'Nieborów'),(2379, 'Niebylec'),(1513, 'Niebywalec'),(1767, 'Niechanowo'),
(2325, 'Niechlów'),(19, 'Niechłow'),(386, 'Niedrzwica Duża'),(372, 'Niedźwiada'),(884, 'Niedźwiedź'),(550, 'Niegosławice'),(1723, 'Niegowa'),(478, 'Nielisz'),(387, 'Niemce'),(9, 'Niemcza'),(1229, 'Niemodlin'),
(1088, 'Niepołomice'),(1178, 'Nieporęt'),(165, 'Nieszawa'),(1459, 'Nisko'),(1367, 'Niwiska'),(627, 'Nowa Brzeźnica'),(2380, 'Nowa Dęba'),(1523, 'Nowa Ðęba'),(1369, 'Nowa Karczma'),(47, 'Nowa Ruda'),
(1410, 'Nowa Sarzyna'),(1927, 'Nowa Słupia'),(510, 'Nowa Sól'),(953, 'Nowa Sucha'),(1952, 'Nowa Tomyśl'),(1384, 'Nowa Wieś Lęborska'),(182, 'Nowa Wieś Wielka'),(247, 'Nowe'),(997, 'Nowe Brzesko'),
(511, 'Nowe Miasteczko'),(805, 'Nowe Miasto'),(2160, 'Nowe Miasto Lubawskie'),(1152, 'Nowe Miasto Nad Pilicą'),(2117, 'Nowe Miasto Nad Wartą'),(580, 'Nowe Ostrowy'),(1290, 'Nowe Piekuty'),
(1968, 'Nowe Skalmierzyce'),(2285, 'Nowe Warpno'),(1532, 'Nowinka'),(444, 'Nowodwór'),(2224, 'Nowogard'),(3, 'Nowogrodziec'),(2383, 'Nowogród'),(541, 'Nowogród Bobrzański'),(2282, 'Nowogródek Pomorski'),
(608, 'Nowosolna'),(763, 'Nowy Duninów'),(1696, 'Nowy Dwór'),(1395, 'Nowy Dwór Gdański'),(660, 'Nowy Dwór Maz'),(718, 'Nowy Kawęczyn'),(1869, 'Nowy Korczyn'),(970, 'Nowy Sącz'),(1391, 'Nowy Staw'),
(955, 'Nowy Targ'),(2433, 'Nowy Tomyśl'),(784, 'Nowy Wiśnicz'),(1348, 'Nowy Żmigród'),(1632, 'Nowygród'),(1291, 'Nozdrzec'),(692, 'Nur'),(1677, 'Nurzec-Stacja'),(1188, 'Nysa'),(1959, 'Oborniki'),
(112, 'Oborniki Śląskie'),(2003, 'Obrazów'),(259, 'Obrowo'),(860, 'Obryte'),(2096, 'Obrzycko'),(315, 'Obsza'),(957, 'Ochotnica Dolna'),(1969, 'Odolanów'),(847, 'Odrzywół'),(1824, 'Ogrodzieniec'),
(1112, 'Ojrzeń'),(2184, 'Okonek'),(1886, 'Oksa'),(2164, 'Olecko'),(825, 'Olesno'),(1418, 'Oleszyce'),(83, 'Oleśnica'),(976, 'Olkusz'),(1405, 'Olszanica'),(1104, 'Olszanka'),(673, 'Olszewo-Borki'),
(1862, 'Olszówka'),(1644, 'Olsztyn'),(2190, 'Olsztynek'),(64, 'Olszyna'),(88, 'Oława'),(1954, 'Opalenica'),(1904, 'Opatowiec'),(1684, 'Opatów'),(1822, 'Opatówek'),(1114, 'Opinogóra Górna'),(614, 'Opoczno'),
(1215, 'Opole'),(415, 'Opole Lubelskie'),(581, 'Oporów'),(2080, 'Orchowo'),(1581, 'Orla'),(1468, 'Orły'),(2133, 'Orneta'),(1713, 'Ornontowice'),(986, 'Orońsko'),(1715, 'Orzeszcze'),(2406, 'Orzesze');



INSERT INTO hv_borough (id, name) VALUES (2428, 'Orzysz'),(248, 'Osie'),(723, 'Osieck'),(1445, 'Osieczna'),(4, 'Osiecznica'),(175, 'Osiek'),(1350, 'Osiek Jasielski'),(1863, 'Osiek Mały'),(183, 'Osielsko'),
(230, 'Osięciny'),(2225, 'Osina'),(748, 'Osjaków'),(1397, 'Ostaszewo'),(680, 'Ostrołęka'),(2099, 'Ostroróg'),(2216, 'Ostrowice'),(1986, 'Ostrowiec Świętokrzyski'),(2081, 'Ostrowite'),(2210, 'Ostróda'),
(1480, 'Ostrów'),(373, 'Ostrów Lubelski'),(695, 'Ostrów Mazowiecka'),(1972, 'Ostrów Wielkopolski'),(374, 'Ostrówek'),(1987, 'Ostrzeszów'),(516, 'Ośno Lubuskie'),(987, 'Oświęcim'),(1191, 'Otmuchów'),
(731, 'Otwock'),(512, 'Otyń'),(1231, 'Ozimek'),(776, 'Ozorków'),(1783, 'Ożarowice'),(1966, 'Ożarów'),(1006, 'Ożarów Maz'),(624, 'Pabianice'),(1872, 'Pacanów'),(1136, 'Pacyna'),(1193, 'Paczków'),
(1448, 'Padew Narodowa'),(628, 'Pajęczno'),(2074, 'Pakosław'),(1195, 'Pakosławice'),(208, 'Pakość'),(999, 'Pałecznica'),(1686, 'Panki'),(189, 'Papowo Biskupie'),(918, 'Paprotnia'),(615, 'Paradyż'),
(1313, 'Parchowo'),(421, 'Parczew'),(1129, 'Parysów'),(777, 'Parzęczew'),(2089, 'Pasłęk'),(2221, 'Pasym'),(25, 'Paszowice'),(1314, 'Pawłosiów'),(1733, 'Pawłowice'),(1123, 'Pawłowiczki'),(2026, 'Pawłów'),
(1704, 'Pawonków'),(750, 'Pątnów'),(907, 'Pcim'),(1534, 'Pelplin'),(2205, 'Pełczyce'),(1680, 'Perlejewo'),(1839, 'Perzów'),(15, 'Pęcław'),(648, 'Pęczniew'),(1778, 'Pępowo'),(739, 'Piaseczno'),(449, 'Piaski'),
(821, 'Piastów'),(593, 'Piątek'),(1633, 'Piątnica'),(33, 'Piechowice'),(2138, 'Piecki'),(1727, 'Piekary Śląskie'),(1933, 'Piekoszów'),(156, 'Pielgrzymka'),(2063, 'Pieniężno'),(151, 'Pieńsk'),(1936, 'Pierzchnica'),
(10, 'Pieszyce'),(1750, 'Pietrowice Wielkie'),(1130, 'Pilawa'),(1658, 'Pilchowice'),(1826, 'Pilica'),(1302, 'Pilzno'),(1999, 'Piła'),(11, 'Piława Górna'),(2419, 'Pińczów'),(889, 'Pionki'),(231, 'Piotrków Kujawski'),
(645, 'Piotrków Trybunalski'),(2211, 'Pisz'),(297, 'Piszczac'),(938, 'Piwniczna-Zdrój'),(1200, 'Platerów'),(65, 'Platerówka'),(2018, 'Pleszew'),(1025, 'Pleśna'),(1533, 'Płaska'),(743, 'Płock'),(1216, 'Płoniawy-Bramura'),
(807, 'Płońsk'),(2064, 'Płoskinia'),(2071, 'Płośnica'),(2239, 'Płoty'),(270, 'Płużnica'),(1153, 'Pniewy'),(2049, 'Pobiedziska'),(1645, 'Poczesna'),(650, 'Poddębice'),(422, 'Podedwórze'),(939, 'Podegrodzie'),
(34, 'Podgórzyn'),(1143, 'Podkowa Leśna'),(1784, 'Pogorzela'),(1174, 'Pokój'),(863, 'Pokrzywnica'),(48, 'Polanica-Zdrój'),(988, 'Polanka Wielka'),(2269, 'Polanów'),(2286, 'Police'),(1063, 'Policzna'),
(92, 'Polkowice'),(1125, 'Polska Cerekiew'),(1474, 'Polski Kończuga'),(1749, 'Połajewo'),(2037, 'Połaniec'),(2315, 'Połczyn-Zdrój'),(656, 'Pomiechówek'),(416, 'Poniatowa'),(1786, 'Poniec'),(1234, 'Popielów'),
(2404, 'Popów'),(1688, 'Popþw'),(1724, 'Poraj'),(1577, 'Porąbka'),(1827, 'Poręba'),(1057, 'Poronin'),(2295, 'Postomino'),(1037, 'Poświętne'),(616, 'Poświętnie'),(1432, 'Potęgowo'),(316, 'Potok Górny'),
(346, 'Potok Wielki'),(849, 'Potworów'),(2083, 'Powidz'),(2236, 'Pozezdrze'),(2020, 'Poznań'),(1376, 'Prabuty'),(1207, 'Praszka'),(733, 'Prażmów'),(59, 'Prochowice'),(2362, 'Promna'),(2098, 'Prostki'),
(1001, 'Proszowice'),(1236, 'Prószków'),(1316, 'Pruchnik'),(1250, 'Prudnik'),(113, 'Prusice'),(249, 'Pruszcz'),(1338, 'Pruszcz Gdański'),(822, 'Pruszków'),(838, 'Przasnysz'),(1329, 'Przechlewo'),(991, 'Przeciszów'),
(1450, 'Przecław'),(679, 'Przedbórz'),(1866, 'Przedecz'),(2290, 'Przelewice'),(2163, 'Przemęt'),(93, 'Przemków'),(1471, 'Przemyśl'),(1271, 'Przerośl'),(920, 'Przesmyki'),(97, 'Przeworno'),(1475, 'Przeworsk'),
(559, 'Przewóz'),(1351, 'Przodkowo'),(2226, 'Przybiernów'),(1974, 'Przygodzice'),(2142, 'Przykona'),(1065, 'Przyłęk'),(1646, 'Przyrów'),(2405, 'Przystajń'),(1690, 'Przystań'),(851, 'Przysucha'),(503, 'Przytoczna'),
(1636, 'Przytuły'),(891, 'Przytyk'),(1340, 'Przywidz'),(1559, 'Psary'),(504, 'Pszczew'),(1342, 'Pszczółki'),(1734, 'Pszczyna'),(1807, 'Pszów'),(400, 'Puchaczów'),(1417, 'Puck'),(432, 'Puławy'),(865, 'Pułtusk'),
(1664, 'Puńsk'),(2191, 'Purda'),(1087, 'Puszcza Mariańska'),(2052, 'Puszczykowo'),(2291, 'Pyrzyce'),(1660, 'Pyskowice'),(1506, 'Pysznica'),(2175, 'Pyzdry'),(960, 'Raba Wyżna'),(962, 'Rabka-Zdrój'),(458, 'Rachanie'),
(809, 'Raciąż'),(166, 'Raciążek'),(1751, 'Racibórz'),(2356, 'Raciechowice'),(909, 'Raciecowice'),(898, 'Racławice'),(1272, 'Raczki'),(479, 'Radecznica'),(827, 'Radgoszcz'),(49, 'Radków'),(1808, 'Radlin'),
(1027, 'Radłów'),(870, 'Radom'),(195, 'Radomin'),(681, 'Radomsko'),(1507, 'Radomyśl Nad Sanem'),(1452, 'Radomyśl Wielki'),(1951, 'Radoszyce'),(2274, 'Radowo Małe'),(94, 'Radwanice'),(1318, 'Radymno'),
(765, 'Radzanowo'),(1094, 'Radzanów'),(1854, 'Radziechowy-Wieprz'),(1089, 'Radziejowice'),(232, 'Radziejów'),(1002, 'Radziemice'),(1589, 'Radziłów'),(1785, 'Radzionków'),(1038, 'Radzymin'),(200, 'Radzyń Chełmiński'),
(439, 'Radzyń Podlaski'),(1855, 'Rajcza'),(1591, 'Rajgród'),(1796, 'Rakoniewice'),(1938, 'Raków'),(2371, 'Rakszawa'),(1370, 'Raniżów'),(1975, 'Raszków'),(813, 'Raszyn'),(689, 'Rawa Mazowiecka'),(2076, 'Rawicz'),
(2316, 'Rąbino'),(2206, 'Recz'),(1546, 'Reda'),(1117, 'Regimin'),(2346, 'Regnów'),(691, 'Regonów'),(2336, 'Rejowiec'),(325, 'Rejowiec Fabryczny'),(1127, 'Reńska Wieś'),(967, 'Repki'),(2275, 'Resko'),
(2124, 'Reszel'),(2240, 'Rewal'),(640, 'Ręczno'),(2403, 'Rędziny'),(235, 'Rogowo'),(1961, 'Rogoźno'),(573, 'Rogów'),(201, 'Rogóźno'),(209, 'Rojewo'),(726, 'Rokiciny'),(1327, 'Rokietnica'),(298, 'Rokitno'),
(1092, 'Romna'),(839, 'Ropa'),(1481, 'Ropczyce'),(299, 'Rossosz'),(932, 'Rościszewo'),(1917, 'Rozdrażew'),(2222, 'Rozogi'),(641, 'Rozprza'),(1331, 'Roźwienica'),(2364, 'Różan'),(2213, 'Ruciane-Nida'),
(1953, 'Ruda Maleniecka'),(1755, 'Ruda śląska'),(326, 'Ruda-Huta'),(1583, 'Rudka'),(69, 'Rudna'),(353, 'Rudnik'),(1460, 'Rudnik Nad Sanem'),(1211, 'Rudniki'),(1663, 'Rudziniec'),(60, 'Ruja'),
(1548, 'Rumia'),(567, 'Rusiec'),(854, 'Rusinów'),(1275, 'Rutka-Tartak'),(1298, 'Rutki'),(450, 'Rybczewice'),(1768, 'Rybnik'),(954, 'Rybno'),(2091, 'Rychliki'),(1841, 'Rychtal'),(1883, 'Rychwał'),
(1965, 'Ryczywół'),(1810, 'Rydułtowy'),(1929, 'Rydzyna'),(1029, 'Ryglice'),(2391, 'Ryjewo'),(1377, 'Ryjwo'),(445, 'Ryki'),(1394, 'Rymanów'),(2260, 'Rymań'),(2107, 'Ryn'),(236, 'Rypin'),
(940, 'Rytro'),(2039, 'Rytwiany'),(629, 'Rząśnia'),(1054, 'Rząśnik'),(1330, 'Rzeczenica'),(1189, 'Rzeczniów'),(727, 'Rzeczyca'),(675, 'Rzekuń'),(1033, 'Rzepiennik Strzyżewski'),(517, 'Rzepin'),
(1497, 'Rzeszów'),(1217, 'Rzewnie'),(785, 'Rzezawa'),(609, 'Rzgów'),(969, 'Sabnie'),(226, 'Sadki'),(693, 'Sadkowice'),(1379, 'Sadlinki'),(1967, 'Sadowie'),(1018, 'Sadowne'),(2005, 'Samborzec'),
(2008, 'Sandomierz'),(1137, 'Sanniki'),(1501, 'Sanok'),(492, 'Santok'),(1202, 'Sarnaki'),(327, 'Sawin'),(2048, 'Secemin'),(1665, 'Sejny'),(375, 'Serniki'),(1180, 'Serock'),(405, 'Serokomla'),
(586, 'Sędziejowice'),(1888, 'Sędziszów'),(1482, 'Sędziszów Małopolski'),(2351, 'Sękowa'),(2057, 'Sępopol'),(2334, 'Sępólno Krajeńskie'),(2270, 'Sianów'),(184, 'Sicienko'),(1698, 'Sidra'),
(139, 'Siechnice'),(1170, 'Sieciechów'),(903, 'Siedlce'),(2165, 'Siedlec'),(513, 'Siedlisko'),(328, 'Siedliszcze'),(66, 'Siekierczyn'),(1771, 'Siemianowice Śląskie'),(1682, 'Siemiatycze'),
(1081, 'Siemiątkowo'),(423, 'Siemień'),(630, 'Siemkowice'),(2261, 'Siemyśl'),(1476, 'Sieniawa'),(1240, 'Siennica'),(354, 'Siennica Różana'),(1190, 'Sienno'),(911, 'Siepraw'),(702, 'Sieradz'),
(1352, 'Sierakowice'),(1943, 'Sieraków'),(1976, 'Sieroszewice'),(945, 'Sierpc'),(1562, 'Siewierz'),(1940, 'Sitkówka-Nowiny'),(480, 'Sitno'),(1906, 'Skalbmierz'),(857, 'Skała'),(1106, 'Skarbimierz'),
(1447, 'Skarszew'),(2396, 'Skarszewy'),(894, 'Skaryszew'),(2017, 'Skarżysko Kościelne'),(2019, 'Skarżysko-Kamienna'),(859, 'Skawina'),(530, 'Skąpe'),(216, 'Skępe'),(481, 'Skierbieszów'),(706, 'Skierniewice'),
(1611, 'Skoczół'),(2402, 'Skoczów'),(2157, 'Skoki'),(1353, 'Skołyszyn'),(752, 'Skomlin'),(1197, 'Skoroszyce'),(1449, 'Skórcz'),(922, 'Skórzec'),(237, 'Skrwilno'),(1034, 'Skrzyszów'),(1889, 'Skulsk'),
(505, 'Skwierzyna'),(1873, 'Sloec-Zdrój'),(901, 'Słaboszów'),(533, 'Sława'),(300, 'Sławatycze'),(1563, 'Sławków'),(617, 'Sławno'),(2317, 'Sławoborze'),(861, 'Słomniki'),(525, 'Słońsk'),(885, 'Słopnice'),
(518, 'Słubice'),(2085, 'Słupca'),(719, 'Słupia'),(1891, 'Słupia Jędrzejowska'),(1955, 'Słupia Konecka'),(769, 'Słupno'),(1420, 'Słupsk'),(1451, 'Smętowo Graniczne'),(1435, 'Smołdzino'),(1956, 'Smyków'),
(728, 'Sobienie - Jeziory'),(2367, 'Sobienie-Jeziory'),(1892, 'Sobków'),(1131, 'Sobolew'),(140, 'Sobótka'),(956, 'Sochaczew'),(811, 'Sochocin'),(768, 'Sokolniki'),(1493, 'Sokołów Małopolski'),(973, 'Sokołów Podlaski'),
(1292, 'Sokoły'),(1700, 'Sokółka'),(185, 'Solec Kujawski'),(1192, 'Solec Nad Wisłą'),(2413, 'Solec-Zdrój'),(1406, 'Solina'),(1056, 'Somianka'),(1354, 'Somonino'),(1890, 'Sompolno'),(1118, 'Sońsk'),
(1438, 'Sopot'),(2144, 'Sorkwity'),(1773, 'Sosnowiec'),(424, 'Sosnownica'),(301, 'Sosnówka'),(1666, 'Sośnicowice'),(1977, 'Sośnie'),(240, 'Sośno'),(401, 'Spiczyn'),(1074, 'Sputkowice'),
(964, 'Spytkowice'),(2126, 'Srokowo'),(1508, 'Stalowa Wola'),(406, 'Stanin'),(1242, 'Stanisławów'),(788, 'Stara Biała'),(1097, 'Stara Błotnica'),(2303, 'Stara Dąbrowa'),(35, 'Stara Kamienica'),
(2390, 'Stara Kiszewa'),(1203, 'Stara Kornica'),(2028, 'Starachowice'),(1648, 'Starcza'),(1008, 'Stare Babice'),(124, 'Stare Bogaczowice'),(2248, 'Stare Czarnowo'),(2100, 'Stare Juchy'),
(2342, 'Stare Kurowo'),(1893, 'Stare Miasto'),(2394, 'Stare Pole'),(2304, 'Stargard Szczeciński'),(1517, 'Starogard Gdański'),(790, 'Staroźreby'),(467, 'Stary Brus'),(2397, 'Stary Dzierzgoń'),
(1421, 'Stary Dzików'),(698, 'Stary Lubotyń'),(941, 'Stary Sącz'),(1526, 'Stary Targ'),(2340, 'Stary Zamość'),(482, 'Stary Zamoźć'),(2040, 'Staszów'),(2194, 'Stawiguda'),(1621, 'Stawiski'),
(1823, 'Stawiszyn'),(1958, 'Stąporków'),(1398, 'Stegna'),(2228, 'Stepnica'),(975, 'Sterdyń'),(2056, 'Stęszew'),(446, 'Stężyca'),(1021, 'Stoczek'),(407, 'Stoczek Łukowski'),(190, 'Stolno'),
(1875, 'Stopnica'),(146, 'Stoszowice'),(1040, 'Strachówka'),(1941, 'Strawczyn'),(1099, 'Stromiec'),(50, 'Stronie Śląskie'),(1614, 'Strumień'),(778, 'Stryków'),(1013, 'Stryszawa'),(1076, 'Stryszów'),
(2086, 'Strzałkowo'),(108, 'Strzegom'),(1256, 'Strzegowo'),(582, 'Strzelce'),(521, 'Strzelce Krajeńskie'),(1266, 'Strzelce Opolskie'),(631, 'Strzelce Wielkie'),(1165, 'Strzeleczki'),
(98, 'Strzelin'),(222, 'Strzelno'),(388, 'Strzyżewice'),(1514, 'Strzyżów'),(1469, 'Stubno'),(1315, 'Studzienice'),(1257, 'Stupsk'),(1537, 'Subkowy'),(1015, 'Sucha Beskidzka'),(2305, 'Suchań');

INSERT INTO hv_borough (id, name) VALUES (2021, 'Suchedniów'),(1702, 'Suchowola'),(923, 'Suchożebry'),(1343, 'Suchy Dąb'),(2058, 'Suchy Las'),(542, 'Sulechów'),(642, 'Sulejów'),(1245, 'Sulejówek'),
(526, 'Sulęcin'),(1358, 'Sulęczyno'),(152, 'Sulików'),(632, 'Sulmierzyce'),(912, 'Sułkowice'),(862, 'Sułoszowa'),(2341, 'Sułów'),(1557, 'Supraśl'),(1560, 'Suraż'),(459, 'Susiec'),(2116, 'Susz'),
(1738, 'Suszec'),(1280, 'Suwałki'),(2062, 'Swarzędz'),(84, 'Syców'),(483, 'Syłów'),(1219, 'Sypniewo'),(771, 'Szadek'),(966, 'Szaflary'),(1740, 'Szamocin'),(2103, 'Szamotuły'),(360, 'Szastarka'),(531, 'Szczaniec'),
(1138, 'Szczawin Kościelny'),(968, 'Szczawnica'),(125, 'Szczawno-Zdrój'),(484, 'Szczebrzeszyn'),(2308, 'Szczecin'),(2313, 'Szczecinek'),(1830, 'Szczekociny'),(568, 'Szczerców'),(828, 'Szczucin'),(1593, 'Szczuczyn'),
(799, 'Szczurowa'),(946, 'Szczutowo'),(1579, 'Szczyrk'),(51, 'Szczytna'),(1825, 'Szczytniki'),(2227, 'Szczytno'),(1220, 'Szelków'),(1550, 'Szemud'),(2385, 'Szepietowo'),(1036, 'Szerzyny'),(36, 'Szklarska Poręba'),
(534, 'Szlichtyngowa'),(1293, 'Szpietkowo'),(551, 'Szprotawa'),(1258, 'Szreńsk'),(1535, 'Sztabin'),(1527, 'Sztum'),(1399, 'Sztutowo'),(227, 'Szubin'),(1705, 'Szudziałowo'),(712, 'Szulborze Wielkie'),(2387, 'Szumowo'),
(989, 'Szydłowiec'),(1259, 'Szydłowo'),(2420, 'Szydłów'),(1299, 'Szymowo'),(1276, 'Szypliszki'),(70, 'Ścinawa'),(1857, 'Ślemień'),(2431, 'Ślesin'),(1894, 'Śleśin'),(266, 'Śliwice'),(1905, 'Śmigiel'),(1639, 'Śniadowo'),
(2132, 'Śrem'),(103, 'Środa Śląska'),(2119, 'Środa Wielkopolska'),(2196, 'Świątki'),(864, 'Świątniki Górne'),(109, 'Świdnica'),(451, 'Świdnik'),(2318, 'Świdwin'),(110, 'Świebodzice'),(532, 'Świebodzin'),
(250, 'Świecie'),(202, 'Świecie Nad Osą'),(176, 'Świedziebnia'),(251, 'Świekatowo'),(67, 'Świeradów-Zdrój'),(866, 'Świercze'),(1176, 'Świerczów'),(1788, 'Świerklaniec'),(1766, 'Świerklany'),(157, 'Świerzawa'),(2255, 'Świerzno'),
(2271, 'Świeszyno'),(1775, 'Świetochłowice'),(1931, 'Święciechowa'),(141, 'Święta Katarzyna'),(2230, 'Świętajno'),(2410, 'Świętochłowice'),(1494, 'Świlcza'),(594, 'Świnice Warckie'),(1858, 'Świnna'),
(2320, 'Świnoujście /na Wyspie Uznam'),(2319, 'Świnoujście /na Wyspie Wolin'),(741, 'Tarczyn'),(2417, 'Tarłów'),(460, 'Tarnawatka'),(1516, 'Tarnobrzeg'),(317, 'Tarnogród'),(1355, 'Tarnowiec'),(2065, 'Tarnowo Podgórne'),
(1789, 'Tarnowskie Góry'),(2361, 'Tarnów'),(1238, 'Tarnów Opolski'),(2187, 'Tarnówka'),(1538, 'Tczew'),(1066, 'Tczów'),(461, 'Telatyn'),(958, 'Teresin'),(302, 'Terespol'),(318, 'Tereszpol'),(217, 'Tłuchowo'),
(1041, 'Tłuszcz'),(915, 'Tokarnia'),(2093, 'Tolkmicko'),(462, 'Tomaszów Lubelski'),(734, 'Tomaszów Mazowiecki'),(1078, 'Tomice'),(233, 'Topólka'),(253, 'Toruń'),(527, 'Torzym'),(1667, 'Toszek'),(452, 'Trawniki'),
(1345, 'Trąbki Wielkie'),(1132, 'Trojanów'),(678, 'Troszyn'),(1477, 'Tryńcza'),(560, 'Trzbiel'),(786, 'Trzciana'),(1752, 'Trzcianka'),(1659, 'Trzcianne'),(506, 'Trzciel'),(1843, 'Trzcinica'),(2249, 'Trzcińsko-Zdrój'),
(2241, 'Trzebiatów'),(544, 'Trzebiechów'),(1317, 'Trzebielno'),(408, 'Trzebieszów'),(814, 'Trzebinia'),(114, 'Trzebnica'),(1495, 'Trzebownisko'),(1769, 'Trzemeszno'),(337, 'Trzeszczany'),(977, 'Trzyciąż'),(361, 'Trzydnik Duży'),
(267, 'Tuchola'),(1319, 'Tuchomie'),(1039, 'Tuchów'),(1876, 'Tuczępy'),(303, 'Tuczna'),(2323, 'Tuczno'),(2145, 'Tuliszków'),(1241, 'Tułowice'),(561, 'Tuplice'),(1243, 'Turawa'),(2146, 'Turek'),(319, 'Turobin'),
(1623, 'Turośl'),(1561, 'Turośń Kościelna'),(1453, 'Tuszów Narodowy'),(2345, 'Tuszyn'),(85, 'Twardogóra'),(1792, 'Tworóg'),(2195, 'Tychowo'),(1795, 'Tychy'),(1496, 'Tyczyn'),(1564, 'Tykocin'),(888, 'Tymbark'),
(1502, 'Tyrawa Wołoska'),(463, 'Tyszowce'),(338, 'Uchanie'),(104, 'Udanin'),(735, 'Ujazd'),(1859, 'Ujsoły'),(2002, 'Ujście'),(440, 'Ulan-Majorat'),(1461, 'Ulanów'),(464, 'Ulhówek'),(447, 'Ułęż'),(651, 'Uniejów'),
(191, 'Unisław'),(468, 'Urszulin'),(362, 'Urzędów'),(1437, 'Ustka'),(2262, 'Ustronie Morskie'),(1616, 'Ustroń'),(1277, 'Ustrzyki Dolne'),(841, 'Uście Gorlickie'),(376, 'Uścimów'),(1079, 'Wadowice'),(1454, 'Wadowice Górne'),
(167, 'Waganiec'),(1167, 'Walce'),(126, 'Walim'),(127, 'Wałbrzych'),(2324, 'Wałcz'),(2159, 'Wapno'),(1154, 'Warka'),(252, 'Warlubie'),(2292, 'Warnice'),(990, 'Warszawa'),(703, 'Warta'),(5, 'Warta Bolesławiecka'),
(652, 'Wartkowice'),(2381, 'Wasilków'),(1989, 'Waśniów'),(271, 'Wąbrzeźno'),(2030, 'Wąchock'),(26, 'Wądroże Wielkie'),(2161, 'Wągrowiec'),(238, 'Wąpielsk'),(713, 'Wąsewo'),(20, 'Wąsosz'),(433, 'Wąwolnica'),
(1551, 'Wejcherowo'),(2399, 'Wejherowo'),(339, 'Werbkowice'),(1861, 'Węgierska Górka'),(153, 'Węgliniec'),(1735, 'Węgorzewo'),(2276, 'Węgorzyno'),(1023, 'Węgrów'),(99, 'Wiazów'),(729, 'Wiązowna'),(1334, 'Wiązownica'),
(2329, 'Wiązów'),(1385, 'Wicko'),(587, 'Widawa'),(2250, 'Widuchowa'),(1260, 'Wieczfina Kościelna'),(2366, 'Wieczfnia Kościelna'),(2232, 'Wielbark'),(1754, 'Wieleń'),(218, 'Wielgie'),(682, 'Wielgomłyny'),
(1798, 'Wielichowo'),(1090, 'Wieliczka'),(2425, 'Wieliczki'),(2166, 'Wieliczko'),(1182, 'Wieliszew'),(260, 'Wielka Nieszawka'),(867, 'Wielka Wieś'),(1423, 'Wielkie Oczy'),(1483, 'Wielopole Skrzyńskie'),(1670, 'Wielowieś'),
(754, 'Wieluń'),(856, 'Wieniawa'),(1080, 'Wieprz'),(770, 'Wieruszów'),(329, 'Wierzbica'),(1895, 'Wierzbinek'),(1026, 'Wierzbno'),(756, 'Wierzchlas'),(1042, 'Wierzchosławice'),(2217, 'Wierzchowo'),(1045, 'Wietrzychowice'),
(241, 'Więcbork'),(1932, 'Wijewo'),(1582, 'Wilamowice'),(2422, 'Wilczęta'),(2066, 'Wilczęta Działdowo'),(2009, 'Wilczyce'),(1896, 'Wilczyn'),(1133, 'Wilga'),(363, 'Wilkołaz'),(1585, 'Wilkowice'),(417, 'Wilków'),
(868, 'Winnica'),(130, 'Wińsko'),(1567, 'Wisilków'),(1093, 'Wiskitki'),(1618, 'Wisła'),(115, 'Wisznia Mała'),(304, 'Wisznice'),(1878, 'Wiślica'),(924, 'Wiśniew'),(1261, 'Wiśniewo'),(916, 'Wiśniowa'),
(1770, 'Witkowo'),(493, 'Witnica'),(595, 'Witonia'),(1640, 'Wizna'),(1278, 'Wiżajny'),(75, 'Wleń'),(1419, 'Władysławowo'),(2147, 'Władysławów'),(272, 'Włocławek'),(469, 'Włodawa'),(1832, 'Włodowice'),(2432, 'Włoszakowice'),
(2050, 'Włoszczowa'),(925, 'Wodynie'),(588, 'Wodzierady'),(1897, 'Wodzisław'),(1812, 'Wodzisław Śląski'),(441, 'Wohyń'),(1396, 'Wojaszówka'),(1970, 'Wojciechowice'),(389, 'Wojciechów'),(409, 'Wojcieszków'),(158, 'Wojcieszów'),
(1565, 'Wojkowice'),(1047, 'Wojnicz'),(330, 'Wojsławice'),(643, 'Wola Krzysztoporska'),(410, 'Wola Mysłowska'),(470, 'Wola Uhruska'),(899, 'Wolanów'),(644, 'Wolbórz'),(979, 'Wolbrom'),(2256, 'Wolin'),
(2167, 'Wolsztyn'),(1161, 'Wołczyn'),(1043, 'Wołomin'),(131, 'Wołów'),(1706, 'Woźniki'),(390, 'Wólka'),(1692, 'Wręczyca Wielka'),(132, 'Wrocław'),(2105, 'Wronki'),(704, 'Wróblew'),(2437, 'Września'),
(2177, 'Wrześnica'),(535, 'Wschowa'),(2108, 'Wydminy'),(2004, 'Wykrzysk'),(552, 'Wymiarki'),(1716, 'Wyry'),(471, 'Wyryki'),(2434, 'Wyrzysk'),(2006, 'Wysoka'),(391, 'Wysokie'),(1296, 'Wysokie Mazowieckie'),
(1584, 'Wyszki'),(1058, 'Wyszków'),(791, 'Wyszogród'),(1101, 'Wyśmierzyce'),(1575, 'Y'),(871, 'Zabierzów'),(1569, 'Zabłudów'),(545, 'Zabór'),(1060, 'Zabrodzie'),(1814, 'Zabrze'),(1478, 'Zabrzecze'),
(654, 'Zadzim'),(1944, 'Zagnańsk'),(2090, 'Zagórów'),(1503, 'Zagórz'),(159, 'Zagrodno'),(1048, 'Zakliczyn'),(1509, 'Zaklików'),(1062, 'Zakopane'),(658, 'Zakroczym'),(392, 'Zakrzew'),(168, 'Zakrzewo'),
(364, 'Zakrzówek'),(305, 'Zalesie'),(1510, 'Zaleszany'),(2118, 'Zalewo'),(812, 'Załuski'),(1301, 'Zambrów'),(486, 'Zamość'),(2123, 'Zaniemyśl'),(772, 'Zapolice'),(714, 'Zaręby Kościelne'),(1504, 'Zarszyn'),
(2375, 'Zarzecze'),(992, 'Zator'),(869, 'Zatory'),(1570, 'Zawady'),(1270, 'Zawadzkie'),(2011, 'Zawichost'),(154, 'Zawidów'),(947, 'Zawidz'),(1834, 'Zawiercie'),(1016, 'Zawoja'),(116, 'Zawonia'),
(1044, 'Ząbki'),(147, 'Ząbkowice Śląskie'),(2343, 'Zbąszynek'),(1957, 'Zbąszyń'),(177, 'Zbiczno'),(1519, 'Zblewo'),(1642, 'Zbójna'),(196, 'Zbójno'),(1794, 'Zbrosławice'),(927, 'Zbuczyn'),(604, 'Zduny'),
(773, 'Zduńska Wola'),(1168, 'Zdzieszowice'),(1619, 'Zebrzydowice'),(569, 'Zelów'),(1019, 'Zembrzyce'),(1214, 'Zębowice'),(779, 'Zgierz'),(155, 'Zgorzelec'),(536, 'Zielona Góra'),(1046, 'Zielonka'),
(872, 'Zielonki'),(148, 'Ziębice'),(261, 'Zławieś Wielka'),(2219, 'Złocieniec'),(705, 'Złoczew'),(1993, 'Złota'),(210, 'Złotniki Kujawskie'),(160, 'Złotoryja'),(2189, 'Złotów'),(149, 'Złoty Stok'),
(522, 'Zwierzyn'),(485, 'Zwierzyniec'),(1068, 'Zwoleń'),(1144, 'Żabia Wola'),(1050, 'Żabno'),(553, 'Żagań'),(1725, 'Żarki'),(1836, 'Żarnowiec'),(618, 'Żarnów'),(111, 'Żarów'),(562, 'Żary'),
(787, 'Żegocina'),(1828, 'Żelazków'),(738, 'Żelechlinek'),(2363, 'Żelechów'),(1805, 'Żerków'),(117, 'Żmigród'),(331, 'Żmudź'),(289, 'Żnin'),(1433, 'Żołynia'),(1837, 'Żory'),(355, 'Żółkiewka'),(142, 'Żórawina'),
(16, 'Żukowice'),(1359, 'Żukowo'),(1470, 'Żurawica'),(1083, 'Żuromin'),(583, 'żychlin'),(1305, 'Żyraków'),(1095, 'Żyrardów'),(434, 'Żyrzyn'),(683, 'Żytno'),(1864, 'Żywiec');



INSERT INTO hv_pcb (id,province,county,borough) VALUES 
(1,1,1,1),(2,1,1,2),(3,1,1,3),(4,1,1,4),(5,1,1,5),(6,1,423,6),(7,1,423,7),(8,1,423,8),(9,1,423,9),(10,1,423,10),(11,1,423,11),(12,1,2,12),(13,1,2,13),(14,1,2,14),
(15,1,2,15),(16,1,2,16),(17,1,3,17),(18,1,3,18),(19,1,3,19),(20,1,3,20),(21,1,4,21),
(22,1,4,22),(23,1,4,23),(24,1,4,24),(25,1,4,25),(26,1,4,26),(27,1,5,27),(28,1,6,28),(29,1,6,29),(30,1,6,30),(31,1,6,31),
(32,1,6,32),(33,1,6,33),(34,1,6,34),(35,1,6,35),(36,1,6,36),(37,1,7,37),(38,1,7,38),(39,1,7,39),(40,1,8,40),(41,1,8,41),(42,1,8,42),(43,1,8,43),(44,1,8,44),(45,1,8,45),(46,1,8,46),(47,1,8,47),
(48,1,8,48),(49,1,8,49),(50,1,8,50),(51,1,8,51),(52,1,9,52),(53,1,10,53),(54,1,10,54),(55,1,10,55),(56,1,10,2326),(57,1,10,57),(58,1,10,59),(59,1,10,60),(60,1,11,61),(61,1,11,63),(62,1,11,64),
(63,1,11,65),(64,1,11,66),(65,1,11,67),(66,1,425,68),(67,1,425,69),(68,1,425,70),(69,1,12,71),(70,1,12,72),(71,1,12,73),(72,1,12,74),(73,1,12,75),(74,1,13,76),(75,1,13,77),(76,1,13,78),
(77,1,14,79),(78,1,14,80),(79,1,14,2327),(80,1,14,2328),(81,1,14,83),(82,1,14,84),(83,1,14,85),(84,1,15,86),(85,1,15,87),(86,1,15,88),(87,1,16,89),(88,1,16,90),(89,1,16,91),(90,1,16,92),
(91,1,16,93),(92,1,16,94),(93,1,17,95),(94,1,17,96),(95,1,17,97),(96,1,17,98),(97,1,17,2329),(98,1,18,100),(99,1,18,101),(100,1,18,102),(101,1,18,103),(102,1,18,104),(103,1,19,105),
(104,1,19,106),(105,1,19,2330),(106,1,19,108),(107,1,19,109),(108,1,19,110),(109,1,19,111),(110,1,20,112),(111,1,20,113),(112,1,20,114),(113,1,20,115),(114,1,20,116),(115,1,20,117),
(116,1,21,118),(117,1,21,119),(118,1,21,120),(119,1,21,122),(120,1,21,123),(121,1,21,124),(122,1,21,125),(123,1,21,126),(124,1,21,127),(125,1,22,128),(126,1,22,130),(127,1,22,131),(128,1,23,132),
(129,1,24,133),(130,1,24,134),(131,1,24,135),(132,1,24,136),(133,1,24,137),(134,1,24,138),(135,1,24,139),(136,1,24,140),(137,1,24,141),(138,1,24,142),(139,1,25,143),(140,1,25,144),(141,1,25,145),
(142,1,25,146),(143,1,25,147),(144,1,25,148),(145,1,25,149),(146,1,26,150),(147,1,26,151),(148,1,26,152),(149,1,26,153),(150,1,26,154),(151,1,26,155),(152,1,27,156),(153,1,27,157),(154,1,27,158),
(155,1,27,159),(156,1,27,160),(157,2,28,161),(158,2,28,162),(159,2,28,2331),(160,2,28,164),(161,2,28,165),(162,2,28,166),(163,2,28,167),(164,2,28,168),(165,2,29,169),(166,2,29,170),(167,2,29,171),
(168,2,29,172),(169,2,29,173),(170,2,29,174),(171,2,29,175),(172,2,29,176),(173,2,29,177),(174,2,30,178),(175,2,30,179),(176,2,30,180),(177,2,30,181),(178,2,30,182),(179,2,30,183),(180,2,30,184),
(181,2,30,185),(182,2,31,2332),(183,2,32,186),(184,2,32,187),(185,2,32,188),(186,2,32,189),(187,2,32,190),(188,2,32,191),(189,2,33,192),(190,2,33,193),(191,2,33,194),(192,2,33,195),(193,2,33,196),
(194,2,34,197),(195,2,35,198),(196,2,35,199),(197,2,35,200),(198,2,35,201),(199,2,35,202),(200,2,36,203),(201,2,36,204),(202,2,36,205),(203,2,36,206),(204,2,36,207),(205,2,36,208),(206,2,36,209),
(207,2,36,210),(208,2,37,211),(209,2,37,212),(210,2,37,213),(211,2,37,214),(212,2,37,215),(213,2,37,216),(214,2,37,217),(215,2,37,218),(216,2,38,219),(217,2,38,220),(218,2,38,221),(219,2,38,222),
(220,2,39,2333),(221,2,39,224),(222,2,39,225),(223,2,39,226),(224,2,39,227),(225,2,40,228),(226,2,40,229),(227,2,40,230),(228,2,40,231),(229,2,40,232),(230,2,40,233),(231,2,41,234),(232,2,41,235),
(233,2,41,236),(234,2,41,237),(235,2,41,238),(236,2,82,239),(237,2,82,2334),(238,2,82,240),(239,2,82,241),(240,2,83,242),(241,2,83,243),(242,2,83,244),(243,2,83,245),(244,2,83,246),(245,2,83,247),
(246,2,83,248),(247,2,83,249),(248,2,83,250),(249,2,83,251),(250,2,83,252),(251,2,84,253),(252,2,85,254),(253,2,85,255),(254,2,85,256),(255,2,85,257),(256,2,85,258),(257,2,85,259),(258,2,85,260),
(259,2,85,261),(260,2,86,262),(261,2,86,263),(262,2,86,264),(263,2,86,265),(264,2,86,266),(265,2,86,267),(266,2,87,268),(267,2,87,269),(268,2,87,270),(269,2,87,271),(270,2,88,272),(271,2,89,273),
(272,2,89,274),(273,2,89,275),(274,2,89,276),(275,2,89,277),(276,2,89,278),(277,2,89,279),(278,2,89,280),(279,2,89,281),(280,2,89,282),(281,2,89,283),(282,2,90,284),(283,2,90,285),(284,2,90,286),
(285,2,90,287),(286,2,90,235),(287,2,90,289),(288,3,91,290),(289,3,91,291),(290,3,91,292),(291,3,91,293),(292,3,91,294),(293,3,91,295),(294,3,91,296),(295,3,91,297),(296,3,91,298),(297,3,91,299),
(298,3,91,300),(299,3,91,301),(300,3,91,302),(301,3,91,303),(302,3,91,304),(303,3,91,305),(304,3,92,306),(305,3,93,307),(306,3,93,308),(307,3,93,309),(308,3,93,310),(309,3,93,311),(310,3,93,312),
(311,3,93,313),(312,3,93,314),(313,3,93,315),(314,3,93,316),(315,3,93,317),(316,3,93,318),(317,3,93,319),(318,3,94,320),(319,3,95,321),(320,3,95,322),(321,3,95,2335),(322,3,95,323),(323,3,95,324),
(324,3,95,2336),(325,3,95,325),(326,3,95,326),(327,3,95,327),(328,3,95,328),(329,3,95,329),(330,3,95,330),(331,3,95,331),(332,3,96,332),(333,3,96,333),(334,3,96,334),(335,3,96,336),(336,3,96,337),
(337,3,96,338),(338,3,96,339),(339,3,97,340),(340,3,97,341),(341,3,97,342),(342,3,97,343),(343,3,97,344),(344,3,97,345),(345,3,97,346),(346,3,98,347),(347,3,98,348),(348,3,98,349),(349,3,98,350),
(350,3,98,351),(351,3,98,352),(352,3,98,353),(353,3,98,354),(354,3,98,355),(355,3,99,356),(356,3,99,2338),(357,3,99,358),(358,3,99,359),(359,3,99,360),(360,3,99,361),(361,3,99,362),(362,3,99,363),
(363,3,99,364),(364,3,100,365),(365,3,100,366),(366,3,100,367),(367,3,100,368),(368,3,100,369),(369,3,100,370),(370,3,100,371),(371,3,100,372),(372,3,100,373),(373,3,100,374),(374,3,100,375),(375,3,100,376),
(376,3,101,377),(377,3,101,378),(378,3,101,379),(379,3,101,380),(380,3,101,381),(381,3,101,382),(382,3,101,383),(383,3,101,384),(384,3,101,385),(385,3,101,386),(386,3,101,387),(387,3,101,388),
(388,3,101,389),(389,3,101,390),(390,3,101,391),(391,3,101,392),(392,3,102,393),(393,3,103,394),(394,3,103,395),(395,3,103,398),(396,3,103,399),(397,3,103,400),(398,3,103,401),(399,3,104,402),
(400,3,104,403),(401,3,104,404),(402,3,104,405),(403,3,104,406),(404,3,104,407),(405,3,104,408),(406,3,104,409),(407,3,104,410),(408,3,105,411),(409,3,105,412),(410,3,105,413),(411,3,105,414),
(412,3,105,415),(413,3,105,416),(414,3,105,417),(415,3,106,418),(416,3,106,419),(417,3,106,420),(418,3,106,421),(419,3,106,422),(420,3,106,423),(421,3,106,424),(422,3,107,425),(423,3,107,426),
(424,3,107,427),(425,3,107,428),(426,3,107,429),(427,3,107,430),(428,3,107,431),(429,3,107,432),(430,3,107,433),(431,3,107,434),(432,3,108,435),(433,3,108,436),(434,3,108,437),(435,3,108,438),
(436,3,108,439),(437,3,108,440),(438,3,108,441),(439,3,109,442),(440,3,109,443),(441,3,109,444),(442,3,109,445),(443,3,109,446),(444,3,109,447),(445,3,19,2339),(446,3,19,449),(447,3,19,450),
(448,3,19,451),(449,3,19,452),(450,3,110,453),(451,3,110,454),(452,3,110,455),(453,3,110,456),(454,3,110,457),(455,3,110,458),(456,3,110,459),(457,3,110,460),(458,3,110,461),(459,3,110,462),
(460,3,110,463),(461,3,110,464),(462,3,111,465),(463,3,111,466),(464,3,111,467),(465,3,111,468),(466,3,111,469),(467,3,111,470),(468,3,111,471),(469,3,112,402),(470,3,112,473),(471,3,112,474),
(472,3,112,475),(473,3,112,476),(474,3,112,477),(475,3,112,478),(476,3,112,479),(477,3,112,480),(478,3,112,481),(479,3,112,2340),(480,3,112,2341),(481,3,112,484),(482,3,112,485),(483,3,113,486),
(484,4,114,487),(485,4,114,488),(486,4,114,489),(487,4,114,490),(488,4,114,491),(489,4,114,492),(490,4,114,493),(491,4,115,494),(492,4,116,495),(493,4,116,496),(494,4,116,497),(495,4,116,498),
(496,4,116,499),(497,4,116,500),(498,4,117,501),(499,4,117,502),(500,4,117,503),(501,4,117,504),(502,4,117,505),(503,4,117,506),(504,4,118,507),(505,4,118,508),(506,4,118,509),(507,4,118,510),
(508,4,118,511),(509,4,118,512),(510,4,118,513),(511,4,119,514),(512,4,119,515),(513,4,119,516),(514,4,119,517),(515,4,119,518),(516,4,120,519),(517,4,120,520),(518,4,120,2342),(519,4,120,521),
(520,4,120,522),(521,4,121,523),(522,4,121,524),(523,4,121,525),(524,4,121,526),(525,4,121,527),(526,4,122,528),(527,4,122,529),(528,4,122,530),(529,4,122,531),(530,4,122,532),(531,4,122,2343),
(532,4,123,533),(533,4,123,534),(534,4,123,535),(535,4,124,536),(536,4,125,537),(537,4,125,538),(538,4,125,539),(539,4,125,540),(540,4,125,541),(541,4,125,542),(542,4,125,109),(543,4,125,544),
(544,4,125,545),(545,4,126,546),(546,4,126,547),(547,4,126,548),(548,4,126,549),(549,4,126,550),(550,4,126,551),(551,4,126,552),(552,4,126,553),(553,4,127,554),(554,4,127,2344),(555,4,127,556),
(556,4,127,557),(557,4,127,558),(558,4,127,559),(559,4,127,560),(560,4,127,561),(561,4,127,562),(562,5,128,563),(563,5,128,564),(564,5,128,565),(565,5,128,566),(566,5,128,567),(567,5,128,568),
(568,5,128,569),(569,5,129,570),(570,5,129,571),(571,5,129,572),(572,5,129,573),(573,5,130,574),(574,5,130,575),(575,5,130,576),(576,5,130,577),(577,5,130,578),(578,5,130,579),(579,5,130,580),
(580,5,130,581),(581,5,130,582),(582,5,130,583),(583,5,131,584),(584,5,131,585),(585,5,131,586),(586,5,131,587),(587,5,131,588),(588,5,132,589),(589,5,132,590),(590,5,132,591),(591,5,132,592),
(592,5,132,593),(593,5,132,594),(594,5,132,595),(595,5,133,596),(596,5,133,597),(597,5,133,598),(598,5,133,599),(599,5,133,600),(600,5,133,601),(601,5,133,602),(602,5,133,603),(603,5,133,604),
(604,5,134,605),(605,5,134,606),(606,5,134,607),(607,5,134,608),(608,5,134,609),(609,5,134,2345),(610,5,135,610),(611,5,136,611),(612,5,136,612),(613,5,136,613),(614,5,136,614),(615,5,136,615),
(616,5,136,616),(617,5,136,617),(618,5,136,618),(619,5,137,619),(620,5,137,620),(621,5,137,621),(622,5,137,622),(623,5,137,623),(624,5,137,624),(625,5,138,625),(626,5,138,626),(627,5,138,627);



INSERT INTO hv_pcb (id,province,county,borough) VALUES 
(628,5,138,628),(629,5,138,629),(630,5,138,630),(631,5,138,631),(632,5,138,632),(633,5,139,307),(634,5,139,634),(635,5,139,635),(636,5,139,636),(637,5,139,637),(638,5,139,638),(639,5,139,640),
(640,5,139,641),(641,5,139,642),(642,5,139,643),(643,5,139,644),(644,5,140,645),(645,5,141,646),(646,5,141,648),(647,5,141,650),(648,5,141,651),(649,5,141,652),(650,5,141,654),(651,5,142,80),
(652,5,142,659),(653,5,142,665),(654,5,142,667),(655,5,142,670),(656,5,142,672),(657,5,142,674),(658,5,142,676),(659,5,142,677),(660,5,142,679),(661,5,142,681),(662,5,142,682),(663,5,142,683),
(664,5,143,685),(665,5,143,687),(666,5,143,689),(667,5,143,2346),(668,5,143,693),(669,5,144,694),(670,5,144,696),(671,5,144,2347),(672,5,144,699),(673,5,144,700),(674,5,144,701),(675,5,144,702),
(676,5,144,703),(677,5,144,704),(678,5,144,705),(679,5,145,706),(680,5,146,707),(681,5,146,708),(682,5,146,709),(683,5,146,710),(684,5,146,711),(685,5,146,716),(686,5,146,718),(687,5,146,719),
(688,5,110,720),(689,5,110,721),(690,5,110,2348),(691,5,110,2349),(692,5,110,725),(693,5,110,726),(694,5,110,727),(695,5,110,734),(696,5,110,735),(697,5,110,738),(698,5,149,740),(699,5,149,742),
(700,5,149,384),(701,5,149,746),(702,5,149,748),(703,5,149,374),(704,5,149,750),(705,5,149,752),(706,5,149,754),(707,5,149,756),(708,5,150,1),(709,5,150,760),(710,5,150,762),(711,5,150,764),
(712,5,150,766),(713,5,150,768),(714,5,150,770),(715,5,151,771),(716,5,151,772),(717,5,151,773),(718,5,152,774),(719,5,152,775),(720,5,152,776),(721,5,152,777),(722,5,152,778),(723,5,152,779),
(724,6,153,780),(725,6,153,781),(726,6,153,782),(727,6,153,783),(728,6,153,784),(729,6,153,785),(730,6,153,786),(731,6,153,787),(732,6,154,789),(733,6,154,792),(734,6,154,794),(735,6,154,795),
(736,6,154,797),(737,6,154,798),(738,6,154,799),(739,6,155,801),(740,6,155,803),(741,6,155,341),(742,6,155,810),(743,6,155,814),(744,6,156,816),(745,6,156,818),(746,6,156,820),(747,6,156,823),
(748,6,156,825),(749,6,156,827),(750,6,156,828),(751,6,157,830),(752,6,157,832),(753,6,157,833),(754,6,157,2350),(755,6,157,836),(756,6,157,638),(757,6,157,839),(758,6,157,2351),(759,6,157,841),
(760,6,158,2352),(761,6,158,844),(762,6,158,845),(763,6,158,2353),(764,6,158,848),(765,6,158,850),(766,6,158,852),(767,6,158,817),(768,6,158,855),(769,6,158,857),(770,6,158,859),(771,6,158,861),
(772,6,158,862),(773,6,158,864),(774,6,158,867),(775,6,158,871),(776,6,158,872),(777,6,159,873),(778,6,160,875),(779,6,160,876),(780,6,160,2354),(781,6,160,878),(782,6,160,879),(783,6,160,881),
(784,6,160,883),(785,6,160,884),(786,6,160,885),(787,6,160,888),(788,6,161,890),(789,6,161,892),(790,6,161,893),(791,6,161,895),(792,6,161,897),(793,6,161,898),(794,6,161,901),(795,6,162,2355),
(796,6,162,904),(797,6,162,905),(798,6,162,907),(799,6,162,2356),(800,6,162,911),(801,6,162,912),(802,6,162,915),(803,6,162,916),(804,6,163,917),(805,6,163,919),(806,6,163,2357),(807,6,163,929),
(808,6,163,2358),(809,6,163,931),(810,6,163,933),(811,6,163,934),(812,6,163,935),(813,6,163,936),(814,6,163,937),(815,6,163,938),(816,6,163,939),(817,6,163,940),(818,6,163,941),(819,6,164,942),
(820,6,164,943),(821,6,164,944),(822,6,164,950),(823,6,164,952),(824,6,164,2359),(825,6,164,955),(826,6,164,957),(827,6,164,960),(828,6,164,962),(829,6,164,964),(830,6,164,966),(831,6,164,968),
(832,6,165,970),(833,6,166,816),(834,6,166,972),(835,6,166,974),(836,6,166,976),(837,6,166,977),(838,6,166,979),(839,6,167,980),(840,6,167,981),(841,6,167,983),(842,6,167,175),(843,6,167,987),
(844,6,167,988),(845,6,167,991),(846,6,167,992),(847,6,168,2360),(848,6,168,996),(849,6,168,997),(850,6,168,999),(851,6,168,1001),(852,6,168,1002),(853,6,169,1004),(854,6,169,1005),(855,6,169,1007),
(856,6,169,1010),(857,6,169,1013),(858,6,169,1015),(859,6,169,1016),(860,6,169,1019),(861,6,170,1020),(862,6,170,1022),(863,6,170,1024),(864,6,170,1025),(865,6,170,1027),(866,6,170,1029),(867,6,170,1033),
(868,6,170,1034),(869,6,170,1036),(870,6,170,1039),(871,6,170,1042),(872,6,170,1045),(873,6,170,1047),(874,6,170,1048),(875,6,170,1050),(876,6,171,2361),(877,6,172,1051),(878,6,172,1053),(879,6,172,1055),
(880,6,172,1057),(881,6,172,1062),(882,6,173,1064),(883,6,173,546),(884,6,173,1069),(885,6,173,1071),(886,6,173,1072),(887,6,173,964),(888,6,173,1076),(889,6,173,1078),(890,6,173,1079),(891,6,173,1080),
(892,6,174,1082),(893,6,174,1085),(894,6,174,1086),(895,6,174,1088),(896,6,174,1090),(897,7,175,1091),(898,7,175,2362),(899,7,175,1094),(900,7,175,1097),(901,7,175,1099),(902,7,175,1101),(903,7,176,1102),
(904,7,176,1105),(905,7,176,1109),(906,7,176,1111),(907,7,176,1112),(908,7,176,1114),(909,7,176,1117),(910,7,176,1118),(911,7,177,1119),(912,7,177,1121),(913,7,177,172),(914,7,177,1124),(915,7,177,1126),
(916,7,177,1128),(917,7,177,1129),(918,7,177,1130),(919,7,177,1131),(920,7,177,1132),(921,7,177,1133),(922,7,177,2363),(923,7,178,1135),(924,7,178,1136),(925,7,178,1137),(926,7,178,1138),(927,7,179,425),
(928,7,179,1140),(929,7,179,1141),(930,7,179,1142),(931,7,179,1143),(932,7,179,1144),(933,7,180,1145),(934,7,180,1146),(935,7,180,1147),(936,7,180,1148),(937,7,180,1149),(938,7,180,1150),(939,7,180,1151),
(940,7,180,1152),(941,7,180,1153),(942,7,180,1154),(943,7,181,1155),(944,7,181,1156),(945,7,181,1159),(946,7,181,1164),(947,7,181,1166),(948,7,181,1169),(949,7,181,1170),(950,7,182,382),(951,7,182,1175),
(952,7,182,1178),(953,7,182,1180),(954,7,182,1182),(955,7,183,1183),(956,7,183,1185),(957,7,183,1186),(958,7,183,1189),(959,7,183,1190),(960,7,183,1192),(961,7,184,1194),(962,7,184,1198),(963,7,184,1104),
(964,7,184,1200),(965,7,184,1202),(966,7,184,1203),(967,7,185,1205),(968,7,185,1208),(969,7,185,1210),(970,7,185,1212),(971,7,185,1213),(972,7,185,1216),(973,7,185,2364),(974,7,185,1217),(975,7,185,1219),
(976,7,185,1220),(977,7,186,1223),(978,7,186,1226),(979,7,186,229),(980,7,186,1230),(981,7,186,1232),(982,7,186,1233),(983,7,186,1235),(984,7,186,1237),(985,7,186,1239),(986,7,186,1240),(987,7,186,1242),
(988,7,186,1245),(989,7,187,1246),(990,7,187,2365),(991,7,187,1254),(992,7,187,1094),(993,7,187,1256),(994,7,187,1257),(995,7,187,1258),(996,7,187,1259),(997,7,187,2366),(998,7,187,1261),(999,7,188,1262),
(1000,7,188,649),(1001,7,188,653),(1002,7,188,656),(1003,7,188,658),(1004,7,188,660),(1005,7,189,661),(1006,7,189,662),(1007,7,189,663),(1008,7,189,664),(1009,7,189,666),(1010,7,189,668),
(1011,7,189,669),(1012,7,189,671),(1013,7,189,673),(1014,7,189,675),(1015,7,189,678),(1016,7,190,680),(1017,7,191,684),(1018,7,191,686),(1019,7,191,688),(1020,7,191,690),(1021,7,191,692),
(1022,7,191,695),(1023,7,191,698),(1024,7,191,712),(1025,7,191,713),(1026,7,191,714),(1027,7,192,715),(1028,7,192,312),(1029,7,192,722),(1030,7,192,723),(1031,7,192,2367),(1032,7,192,729),
(1033,7,192,730),(1034,7,192,731),(1035,7,193,732),(1036,7,193,733),(1037,7,193,736),(1038,7,193,737),(1039,7,193,739),(1040,7,193,741),(1041,7,194,743),(1042,7,195,745),(1043,7,195,747),
(1044,7,195,751),(1045,7,195,753),(1046,7,195,755),(1047,7,195,758),(1048,7,195,759),(1049,7,195,761),(1050,7,195,763),(1051,7,195,765),(1052,7,195,518),(1053,7,195,769),(1054,7,195,788),
(1055,7,195,790),(1056,7,195,791),(1057,7,196,793),(1058,7,196,796),(1059,7,196,800),(1060,7,196,802),(1061,7,196,804),(1062,7,196,805),(1063,7,196,807),(1064,7,196,809),(1065,7,196,811),
(1066,7,196,812),(1067,7,197,813),(1068,7,197,815),(1069,7,197,817),(1070,7,197,819),(1071,7,197,821),(1072,7,197,822),(1073,7,198,824),(1074,7,198,826),(1075,7,198,829),(1076,7,198,831),
(1077,7,198,835),(1078,7,198,838),(1079,7,199,840),(1080,7,199,842),(1081,7,199,843),(1082,7,199,847),(1083,7,199,849),(1084,7,199,851),(1085,7,199,854),(1086,7,199,856),(1087,7,200,858),
(1088,7,200,860),(1089,7,200,863),(1090,7,200,865),(1091,7,200,866),(1092,7,200,868),(1093,7,200,869),(1094,7,201,870),(1095,7,202,874),(1096,7,202,877),(1097,7,202,880),(1098,7,202,882),
(1099,7,202,886),(1100,7,202,887),(1101,7,202,889),(1102,7,202,891),(1103,7,202,894),(1104,7,202,329),(1105,7,202,899),(1106,7,202,392),(1107,7,203,903),(1108,7,204,906),(1109,7,204,908),
(1110,7,204,910),(1111,7,204,913),(1112,7,204,914),(1113,7,204,918),(1114,7,204,920),(1115,7,204,922),(1116,7,204,923),(1117,7,204,924),(1118,7,204,925),(1119,7,204,927),(1120,7,205,928),
(1121,7,205,930),(1122,7,205,932),(1123,7,205,945),(1124,7,205,946),(1125,7,205,947),(1126,7,206,948),(1127,7,206,949),(1128,7,206,2368),(1129,7,206,953),(1130,7,206,954),(1131,7,206,956),
(1132,7,206,958),(1133,7,207,959),(1134,7,207,961),(1135,7,207,963),(1136,7,207,965),(1137,7,207,967),(1138,7,207,969),(1139,7,207,973),(1140,7,207,975),(1141,7,208,978),(1142,7,208,982),
(1143,7,208,984),(1144,7,208,986),(1145,7,208,989),(1146,7,209,990),(1147,7,210,993),(1148,7,210,995),(1149,7,210,998),(1150,7,210,1000),(1151,7,210,1003),(1152,7,210,1006),(1153,7,210,1008),
(1154,7,211,1009),(1155,7,211,1011),(1156,7,211,1012),(1157,7,211,1014),(1158,7,211,1017),(1159,7,211,1018),(1160,7,211,1021),(1161,7,211,1023),(1162,7,211,1026),(1163,7,212,1028),
(1164,7,212,1030),(1165,7,212,1031),(1166,7,212,1032),(1167,7,212,1035),(1168,7,212,616),(1169,7,212,1038),(1170,7,212,1040),(1171,7,212,1041),(1172,7,212,1043),
(1173,7,212,1044),(1174,7,212,1046),(1175,7,213,1049),(1176,7,213,1052),(1177,7,213,1054),(1178,7,213,1056),(1179,7,213,1058),(1180,7,213,1060),(1181,7,214,1061),
(1182,7,214,1063),(1183,7,214,1065),(1184,7,214,1066),(1185,7,214,1068),(1186,7,215,1070),(1187,7,215,1073),(1188,7,215,1075),(1189,7,215,1077),(1190,7,215,1081),(1191,7,215,1083),(1192,7,216,1084),
(1193,7,216,1087),(1194,7,216,1089),(1195,7,216,1093),(1196,7,216,1095),(1197,8,154,1096),(1198,8,154,1098),(1199,8,154,1100),(1200,8,154,1103),(1201,8,154,1104),(1202,8,154,1106),(1203,8,219,1107),
(1204,8,219,1108),(1205,8,219,1110),(1206,8,219,1113),(1207,8,220,1115),(1208,8,220,1116),(1209,8,220,1120),(1210,8,220,1123),(1211,8,220,1125),(1212,8,220,1127),(1213,8,221,1157),(1214,8,221,1158);



INSERT INTO hv_pcb (id,province,county,borough) VALUES 
(1215,8,221,1160),(1216,8,221,1161),(1217,8,222,1162),(1218,8,222,1163),(1219,8,222,1165),(1220,8,222,1167),(1221,8,222,1168),(1222,8,223,1171),(1223,8,223,1173),(1224,8,223,1174),(1225,8,223,1176),
(1226,8,223,417),(1227,8,224,1179),(1228,8,224,1181),(1229,8,224,1184),(1230,8,224,1187),(1231,8,224,1188),(1232,8,224,1191),(1233,8,224,1193),(1234,8,224,1195),(1235,8,224,1197),(1236,8,225,1201),
(1237,8,225,1204),(1238,8,225,825),(1239,8,225,1207),(1240,8,225,1027),(1241,8,225,1211),(1242,8,225,1214),(1243,8,226,1215),(1244,8,105,2369),(1245,8,105,219),(1246,8,105,1222),(1247,8,105,1224),
(1248,8,105,1225),(1249,8,105,1227),(1250,8,105,1229),(1251,8,105,1231),(1252,8,105,1234),(1253,8,105,1236),(1254,8,105,1238),(1255,8,105,1241),(1256,8,105,1243),(1257,8,229,740),(1258,8,229,1247),
(1259,8,229,528),(1260,8,229,1250),(1261,8,230,1251),(1262,8,230,1252),(1263,8,230,1264),(1264,8,230,1265),(1265,8,230,1266),(1266,8,230,735),(1267,8,230,1270),(1268,9,231,1273),(1269,9,231,1274),
(1270,9,231,1277),(1271,9,232,1279),(1272,9,232,1283),(1273,9,232,1284),(1274,9,232,1286),(1275,9,232,2370),(1276,9,232,1291),(1277,9,233,1294),(1278,9,233,1273),(1279,9,233,1297),(1280,9,233,1300),
(1281,9,233,1302),(1282,9,233,1305),(1283,9,234,1306),(1284,9,234,1309),(1285,9,234,1311),(1286,9,234,1314),(1287,9,234,1316),(1288,9,234,1318),(1289,9,234,1327),(1290,9,234,1331),(1291,9,234,1334),
(1292,9,235,1337),(1293,9,235,1337),(1294,9,235,1339),(1295,9,235,1341),(1296,9,235,1344),(1297,9,235,1348),(1298,9,235,1350),(1299,9,235,1353),(1300,9,235,1355),(1301,9,236,1357),(1302,9,236,1360),
(1303,9,236,1362),(1304,9,236,1365),(1305,9,236,1367),(1306,9,236,1370),(1307,9,239,1371),(1308,9,116,1373),(1309,9,116,1375),(1310,9,116,1378),(1311,9,116,1382),(1312,9,116,1386),(1313,9,116,1389),
(1314,9,116,1392),(1315,9,116,1394),(1316,9,116,1396),(1317,9,240,1400),(1318,9,240,1402),(1319,9,240,1403),(1320,9,240,1405),(1321,9,240,1406),(1322,9,241,1407),(1323,9,241,1408),(1324,9,241,1409),
(1325,9,241,1410),(1326,9,242,1411),(1327,9,242,1412),(1328,9,242,1414),(1329,9,242,1416),(1330,9,242,1418),(1331,9,242,1421),(1332,9,242,1423),(1333,9,243,1091),(1334,9,243,1273),(1335,9,243,1429),
(1336,9,243,1430),(1337,9,243,2371),(1338,9,243,1433),(1339,9,244,1434),(1340,9,244,1436),(1341,9,244,1442),(1342,9,244,1444),(1343,9,244,1448),(1344,9,244,1450),(1345,9,244,1452),(1346,9,244,1453),
(1347,9,244,1454),(1348,9,245,1455),(1349,9,245,1456),(1350,9,245,1457),(1351,9,245,1458),(1352,9,245,1459),(1353,9,245,1460),(1354,9,245,1461),(1355,9,246,1462),(1356,9,246,1463),(1357,9,246,1464),
(1358,9,246,1465),(1359,9,246,1466),(1360,9,246,1467),(1361,9,246,1468),(1362,9,246,1469),(1363,9,246,1470),(1364,9,247,1471),(1365,9,248,1472),
(1366,9,248,2372),(1367,9,248,2373),(1368,9,248,2374),(1369,9,248,1475),(1370,9,248,1476),(1371,9,248,1477),(1372,9,248,2375),(1373,9,249,1479),(1374,9,249,1480),(1375,9,249,1481),(1376,9,249,1482),(1377,9,249,1483),(1378,9,250,1484),(1379,9,250,2376),
(1380,9,250,1486),(1381,9,250,1487),(1382,9,250,1488),(1383,9,250,1489),(1384,9,250,323),(1385,9,250,831),(1386,9,250,1492),(1387,9,250,1493),(1388,9,250,1494),(1389,9,250,1495),(1390,9,250,1496),(1391,9,251,1497),(1392,9,252,1498),(1393,9,252,2378),
(1394,9,252,1500),(1395,9,252,1501),(1396,9,252,1502),(1397,9,252,1503),(1398,9,252,1504),(1399,9,253,1505),(1400,9,253,1506),(1401,9,253,1507),(1402,9,253,1508),(1403,9,253,1509),(1404,9,253,1510),(1405,9,254,1511),(1406,9,254,1512),(1407,9,254,2379),
(1408,9,254,1514),(1409,9,254,916),(1410,9,255,1516),(1411,9,256,1518),(1412,9,256,1520),(1413,9,256,1521),(1414,9,256,2380),(1415,10,257,1525),(1416,10,257,1529),(1417,10,257,1531),(1418,10,257,1532),(1419,10,257,1533),(1420,10,257,1535),(1421,10,258,1536),
(1422,10,258,1539),(1423,10,258,1541),(1424,10,258,1543),(1425,10,258,1547),(1426,10,258,1549),(1427,10,258,1552),(1428,10,258,1037),(1429,10,258,1557),(1430,10,258,1560),(1431,10,258,1561),(1432,10,258,1564),(1433,10,258,2381),(1434,10,258,1569),(1435,10,258,1570),
(1436,10,259,1572),(1437,10,260,2382),(1438,10,260,1578),(1439,10,260,1580),(1440,10,260,1581),(1441,10,260,1583),(1442,10,260,1584),(1443,10,261,1587),(1444,10,261,1589),(1445,10,261,1591),(1446,10,261,1593),(1447,10,261,20),(1448,10,262,1599),(1449,10,262,1602),
(1450,10,262,1604),(1451,10,262,1607),(1452,10,262,1608),(1453,10,262,1610),(1454,10,262,1612),(1455,10,262,1613),(1456,10,263,1615),(1457,10,263,1617),(1458,10,263,1620),(1459,10,263,1621),(1460,10,263,1623),(1461,10,264,1625),(1462,10,426,1627),(1463,10,426,1629),
(1464,10,426,2383),(1465,10,426,1633),(1466,10,426,1636),(1467,10,426,1639),(1468,10,426,1640),(1469,10,426,1642),(1470,10,266,1647),(1471,10,266,1649),(1472,10,266,1651),(1473,10,266,1653),(1474,10,266,1656),(1475,10,266,1657),(1476,10,266,1659),(1477,10,267,1661),
(1478,10,267,1662),(1479,10,267,1664),(1480,10,267,1665),(1481,10,268,1668),(1482,10,268,1669),(1483,10,268,1671),(1484,10,268,1673),(1485,10,268,1675),(1486,10,268,1677),(1487,10,268,1680),(1488,10,268,1682),(1489,10,269,1685),(1490,10,269,1628),(1491,10,269,1689),
(1492,10,269,1691),(1493,10,269,1693),(1494,10,269,1696),(1495,10,269,1698),(1496,10,269,1700),(1497,10,269,1702),(1498,10,269,1705),(1499,10,270,1707),(1500,10,270,1267),(1501,10,270,2384),(1502,10,270,1271),(1503,10,270,1272),(1504,10,270,1275),(1505,10,270,1276),
(1506,10,270,1278),(1507,10,271,1280),(1508,10,272,1281),(1509,10,272,1282),(1510,10,272,1285),(1511,10,272,1287),(1512,10,272,1288),(1513,10,272,1290),(1514,10,272,1292),(1515,10,272,2385),(1516,10,272,1296),(1517,10,273,2386),(1518,10,273,1298),(1519,10,273,2387),
(1520,10,273,1301),(1521,11,274,1303),(1522,11,274,1304),(1523,11,274,1307),(1524,11,274,2388),(1525,11,274,1310),(1526,11,274,1312),(1527,11,274,1313),(1528,11,274,1315),(1529,11,274,1317),(1530,11,274,1319),(1531,11,275,1320),(1532,11,275,1321),(1533,11,275,1322),
(1534,11,275,1323),(1535,11,276,1324),(1536,11,276,1325),(1537,11,276,1326),(1538,11,276,1328),(1539,11,276,1329),(1540,11,276,1330),(1541,11,277,1332),(1542,11,278,1333),(1543,11,278,1336),(1544,11,278,1338),(1545,11,278,1340),(1546,11,278,1342),(1547,11,278,1343),
(1548,11,278,1345),(1549,11,279,1346),(1550,11,280,1347),(1551,11,280,1349),(1552,11,280,1351),(1553,11,280,1352),(1554,11,280,1354),(1555,11,280,446),(1556,11,280,1358),(1557,11,280,1359),(1558,11,281,1361),(1559,11,281,2389),(1560,11,281,1364),(1561,11,281,1366),
(1562,11,281,1368),(1563,11,281,1369),(1564,11,281,2390),(1565,11,282,1372),(1566,11,282,1374),(1567,11,282,1376),(1568,11,282,2391),(1569,11,282,1379),(1570,11,283,1380),(1571,11,283,1381),(1572,11,283,1383),(1573,11,283,1384),(1574,11,283,1385),(1575,11,284,2392),
(1576,11,284,2393),(1577,11,284,1390),(1578,11,284,1391),(1579,11,284,2394),(1580,11,188,1393),(1581,11,188,1395),(1582,11,188,1397),(1583,11,188,1398),(1584,11,188,1399),(1585,11,287,1401),(1586,11,287,2395),(1587,11,287,1413),(1588,11,287,1415),(1589,11,287,1417),
(1590,11,287,1419),(1591,11,288,1420),(1592,11,289,1422),(1593,11,289,1424),(1594,11,289,1427),(1595,11,289,1428),(1596,11,289,1431),(1597,11,289,1432),(1598,11,289,1435),(1599,11,289,1437),(1600,11,290,1438),(1601,11,417,1439),(1602,11,417,1440),(1603,11,417,1441),
(1604,11,417,1443),(1605,11,417,1445),(1606,11,417,175),(1607,11,417,2396),(1608,11,417,1449),(1609,11,417,1451),(1610,11,417,1517),(1611,11,417,1519),(1612,11,292,1522),(1613,11,292,1524),(1614,11,292,2397),(1615,11,292,1526),(1616,11,292,1527),(1617,11,293,1528),
(1618,11,293,1530),(1619,11,293,1534),(1620,11,293,1537),(1621,11,293,1538),(1622,11,294,1540),(1623,11,294,1542),(1624,11,294,1544),(1625,11,294,1545),(1626,11,294,2398),(1627,11,294,1546),(1628,11,294,1548),(1629,11,294,1550),(1630,11,294,2399),(1631,12,297,1553),
(1632,12,297,211),(1633,12,297,1556),(1634,12,297,1558),(1635,12,297,1559),(1636,12,297,1562),(1637,12,297,1563),(1638,12,297,1565),(1639,12,260,1566),(1640,12,260,1568),(1641,12,260,1571),(1642,12,260,1573),(1643,12,260,1574),(1644,12,260,2400),(1645,12,260,1577),
(1646,12,260,1579),(1647,12,260,1582),(1648,12,260,1585),(1649,12,298,1586),(1650,12,299,1588),(1651,12,299,1590),(1652,12,299,1592),(1653,12,299,1594),(1654,12,299,1596),(1655,12,300,1597),(1656,12,301,1598),(1657,12,302,1600),(1658,12,302,1601),(1659,12,302,1603),
(1660,12,302,1337),(1661,12,302,2401),(1662,12,302,1606),(1663,12,302,1609),(1664,12,302,2402),(1665,12,302,1614),(1666,12,302,1616),(1667,12,302,1618),(1668,12,302,1619),(1669,12,303,1622),(1670,12,304,1624),(1671,12,304,1626),(1672,12,304,1628),(1673,12,304,1630),
(1674,12,304,1631),(1675,12,304,1634),(1676,12,304,1635),(1677,12,304,1637),(1678,12,304,1638),(1679,12,304,1641),(1680,12,304,1643),(1681,12,304,1644),(1682,12,304,1645),(1683,12,304,1646),(1684,12,304,2403),(1685,12,304,1648),(1686,12,305,1650),(1687,12,306,1652),
(1688,12,307,1654),(1689,12,307,1655),(1690,12,307,1658),(1691,12,307,1660),(1692,12,307,1663),(1693,12,307,1666),(1694,12,307,1667),(1695,12,307,1670),(1696,12,308,1672),(1697,12,309,1674),(1698,12,310,1676),(1699,12,311,1678),(1700,12,311,1679),(1701,12,311,1681),
(1702,12,311,1683),(1703,12,311,1684),(1704,12,311,1686),(1705,12,311,2404),(1706,12,311,2405),(1707,12,311,1692),(1708,12,312,1694),(1709,12,312,1695),(1710,12,312,1697),(1711,12,312,1699),(1712,12,312,1701),(1713,12,312,1703),(1714,12,312,1704),(1715,12,312,1706),
(1716,12,313,1708),(1717,12,313,1710),(1718,12,313,1713),(1719,12,313,1715),(1720,12,313,1716),(1721,12,314,1719),(1722,12,315,1720),(1723,12,315,2407),(1724,12,315,1723),(1725,12,315,1724),(1726,12,315,1725),(1727,12,316,1727),(1728,12,317,1730),(1729,12,317,1731),
(1730,12,317,1732),(1731,12,317,1733),(1732,12,317,1734),(1733,12,317,1738),(1734,12,318,1741),(1735,12,318,1743),(1736,12,318,2408),(1737,12,318,1745),(1738,12,318,1747),(1739,12,318,1750),(1740,12,318,1751),(1741,12,318,353),(1742,12,319,1755),(1743,12,320,1757),
(1744,12,320,1760),(1745,12,320,1762),(1746,12,320,1764),(1747,12,320,1766),(1748,12,321,1768),(1749,12,322,1771),(1750,12,323,1773),(1751,12,324,2410),(1752,12,325,1777),(1753,12,325,1780),(1754,12,325,1782),(1755,12,325,1783),(1756,12,325,1785),(1757,12,325,1788),
(1758,12,325,1789),(1759,12,325,1792),(1760,12,325,1794),(1761,12,326,1795),(1762,12,327,1797),(1763,12,327,1520),(1764,12,327,1802),(1765,12,327,1804),(1766,12,327,1806),(1767,12,327,1807),(1768,12,327,1808),(1769,12,327,1810),(1770,12,327,1812),(1771,12,328,1814),
(1772,12,329,1815),(1773,12,329,1819),(1774,12,329,1820),(1775,12,329,1824),(1776,12,329,1826),(1777,12,329,1827),(1778,12,329,1830),(1779,12,329,1832),(1780,12,329,1834),(1781,12,329,1836),(1782,12,330,1837),(1783,12,331,2352),(1784,12,331,1842),(1785,12,331,1844),
(1786,12,331,1846),(1787,12,331,1847),(1788,12,331,2411),(1789,12,331,2412),(1790,12,331,1850),(1791,12,331,1854),(1792,12,331,1855),(1793,12,331,1857),(1794,12,331,1858),(1795,12,331,1859),(1796,12,331,1861),(1797,12,331,1864),(1798,13,332,1865),(1799,13,332,1867),
(1800,13,332,1869),(1801,13,332,1872),(1802,13,332,2413),(1803,13,332,1875),(1804,13,332,1876),(1805,13,332,1878),(1806,13,333,1880),(1807,13,333,1882),(1808,13,333,1884),(1809,13,333,1885),(1810,13,333,1886),(1811,13,333,1888),(1812,13,333,1891),(1813,13,333,1892),
(1814,13,333,1897),(1815,13,334,1898),(1816,13,334,634),(1817,13,334,1903),(1818,13,334,1904),(1819,13,334,1906),(1820,13,335,2414),(1821,13,336,1908),(1822,13,336,1909),(1823,13,336,1911),(1824,13,336,1486),(1825,13,336,1913),(1826,13,336,1915),(1827,13,336,529),
(1828,13,336,1918),(1829,13,336,2415),(1830,13,336,1921),(1831,13,336,1923),(1832,13,336,1924),(1833,13,336,1927),(1834,13,336,1933),(1835,13,336,1936),(1836,13,336,1938),(1837,13,336,1940),(1838,13,336,1941),(1839,13,336,1944),(1840,13,427,1947),(1841,13,427,1948),
(1842,13,427,1949),(1843,13,427,1951),(1844,13,427,1953),(1845,13,427,1955),(1846,13,427,1956),(1847,13,427,1958),(1848,13,337,1960),(1849,13,337,1962),(1850,13,337,1963),(1851,13,337,1684),(1852,13,337,1966),(1853,13,337,1967),(1854,13,337,2417),(1855,13,337,1970),
(1856,13,428,1971),(1857,13,428,1973),(1858,13,428,1978),(1859,13,428,1984),(1860,13,428,1986),(1861,13,428,1989),(1862,13,339,1991),(1863,13,339,1992),(1864,13,339,2418),(1865,13,339,2419),(1866,13,339,1993),(1867,13,338,1995),(1868,13,338,1996),(1869,13,338,1997),
(1870,13,338,2001),(1871,13,338,2003),(1872,13,338,2005),(1873,13,338,2008),(1874,13,338,2009),(1875,13,338,2011),(1876,13,340,2013),(1877,13,340,2014),(1878,13,340,2017),(1879,13,340,2019),(1880,13,340,2021),(1881,13,341,554),(1882,13,341,2024),(1883,13,341,2026),
(1884,13,341,2028),(1885,13,341,2030),(1886,13,342,2031),(1887,13,342,766),(1888,13,342,83),(1889,13,342,175),(1890,13,342,2037),(1891,13,342,2039),(1892,13,342,2040),(1893,13,342,2420),(1894,13,343,2042),(1895,13,343,2044),(1896,13,343,2046),(1897,13,343,49);



INSERT INTO hv_pcb (id,province,county,borough) VALUES 
(1898,13,343,2048),(1899,13,343,2050),(1900,14,344,2421),(1901,14,344,2053),(1902,14,344,2055),(1903,14,344,2057),(1904,14,345,2059),(1905,14,345,2060),(1906,14,345,2061),(1907,14,345,2063),(1908,14,345,2064),(1909,14,345,2422),(1910,14,346,2423),(1911,14,346,2068),
(1912,14,346,2069),(1913,14,346,2071),(1914,14,346,954),(1915,14,347,2077),(1916,14,348,2079),(1917,14,348,2082),(1918,14,348,2084),(1919,14,348,2087),(1920,14,348,2088),(1921,14,348,2089),(1922,14,348,2091),(1923,14,348,2093),(1924,14,349,2095),(1925,14,349,2097),
(1926,14,349,2098),(1927,14,349,2100),(1928,14,350,2102),(1929,14,350,2104),(1930,14,350,2106),(1931,14,350,2107),(1932,14,350,2108),(1933,14,351,2110),(1934,14,351,2424),(1935,14,351,2111),(1936,14,352,2112),(1937,14,352,2114),(1938,14,352,2115),(1939,14,352,2116),
(1940,14,352,2118),(1941,14,353,2120),(1942,14,353,2121),(1943,14,353,2122),(1944,14,353,2124),(1945,14,353,2126),(1946,14,354,2128),(1947,14,354,2129),(1948,14,354,2131),(1949,14,354,2133),(1950,14,355,2134),(1951,14,355,2137),(1952,14,355,2138),(1953,14,355,2144),
(1954,14,356,2148),(1955,14,356,2149),(1956,14,356,2150),(1957,14,356,2152),(1958,14,357,2153),(1959,14,357,2155),(1960,14,357,2158),(1961,14,357,2160),(1962,14,358,2162),(1963,14,358,2164),(1964,14,358,2230),(1965,14,358,2425),(1966,14,359,1644),(1967,14,360,2171),
(1968,14,360,2153),(1969,14,360,2176),(1970,14,360,2178),(1971,14,360,2180),(1972,14,360,2182),(1973,14,360,2185),(1974,14,360,1617),(1975,14,360,2190),(1976,14,360,2191),(1977,14,360,2194),(1978,14,360,2196),(1979,14,361,2199),(1980,14,361,2426),(1981,14,361,2201),
(1982,14,361,2203),(1983,14,361,2204),(1984,14,361,2207),(1985,14,361,2208),(1986,14,361,2210),(1987,14,362,2427),(1988,14,362,2428),(1989,14,362,2211),(1990,14,362,2213),(1991,14,363,2215),(1992,14,363,2218),(1993,14,363,2221),(1994,14,363,2222),(1995,14,363,2227),
(1996,14,363,2230),(1997,14,363,2232),(1998,14,429,2429),(1999,14,429,2236),(2000,14,429,1735),(2001,15,364,1736),(2002,15,364,1737),(2003,15,364,1739),(2004,15,364,1740),(2005,15,365,1742),(2006,15,365,1744),(2007,15,365,1746),(2008,15,365,1748),(2009,15,365,1749),
(2010,15,365,1752),(2011,15,365,1754),(2012,15,366,1756),(2013,15,366,1758),(2014,15,366,1759),(2015,15,366,1761),(2016,15,366,1763),(2017,15,366,2430),(2018,15,366,1767),(2019,15,366,1769),
(2020,15,366,1770),(2021,15,369,1772),(2022,15,369,1774),(2023,15,369,1776),(2024,15,369,1778),(2025,15,369,449),(2026,15,369,1784),(2027,15,369,1786),(2028,15,179,1787),(2029,15,179,1790),(2030,15,179,1793),
(2031,15,179,1796),(2032,15,179,1798),(2033,15,370,1800),(2034,15,370,1456),(2035,15,370,1803),(2036,15,370,1805),(2037,15,371,1809),(2038,15,371,570),(2039,15,371,1813),(2040,15,371,1816),(2041,15,371,1817),
(2042,15,371,1818),(2043,15,371,1821),(2044,15,371,1822),(2045,15,371,1823),(2046,15,371,1825),(2047,15,371,1828),(2048,15,372,1829),(2049,15,373,425),(2050,15,373,1833),(2051,15,373,1835),(2052,15,373,1838),
(2053,15,373,1839),(2054,15,373,1841),(2055,15,373,1843),(2056,15,374,1845),(2057,15,374,1848),(2058,15,374,497),(2059,15,374,1852),(2060,15,374,489),(2061,15,374,1856),(2062,15,374,1860),(2063,15,374,1862),
(2064,15,374,1863),(2065,15,374,1866),(2066,15,375,1868),(2067,15,376,1870),(2068,15,376,1871),(2069,15,376,1874),(2070,15,376,1877),(2071,15,376,1879),(2072,15,376,1881),(2073,15,376,1883),(2074,15,376,609),
(2075,15,376,1889),(2076,15,376,1890),(2077,15,376,1893),(2078,15,376,2431),(2079,15,376,1895),(2080,15,376,1896),(2081,15,377,1899),(2082,15,377,1901),(2083,15,377,1902),(2084,15,377,1905),(2085,15,378,1907),
(2086,15,378,1910),(2087,15,378,1914),(2088,15,378,1917),(2089,15,378,632),(2090,15,378,604),(2091,15,379,1925),(2092,15,379,215),(2093,15,379,1445),(2094,15,379,1929),(2095,15,379,1931),(2096,15,379,1932),
(2097,15,379,2432),(2098,15,380,1000),(2099,15,381,1937),(2100,15,381,1939),(2101,15,381,1942),(2102,15,381,1943),(2103,15,382,1945),(2104,15,382,1946),(2105,15,382,1950),(2106,15,382,2433),(2107,15,382,1954),
(2108,15,382,1957),(2109,15,385,1959),(2110,15,385,1961),(2111,15,385,1965),(2112,15,191,1968),(2113,15,191,1969),(2114,15,191,1972),(2115,15,191,1974),(2116,15,191,1975),(2117,15,191,1976),(2118,15,191,1977),
(2119,15,386,1979),(2120,15,386,1980),(2121,15,386,1981),(2122,15,386,1982),(2123,15,386,1983),(2124,15,386,1985),(2125,15,386,1987),(2126,15,387,1988),(2127,15,387,1990),(2128,15,387,1994),(2129,15,387,1998),
(2130,15,387,1999),(2131,15,387,1259),(2132,15,387,2002),(2133,15,387,2434),(2134,15,387,2006),(2135,15,388,2007),(2136,15,388,1436),(2137,15,388,2012),(2138,15,388,2015),(2139,15,388,2016),(2140,15,388,2018),
(2141,15,389,2020),(2142,15,390,2022),(2143,15,390,2025),(2144,15,390,2027),(2145,15,390,2435),(2146,15,390,2032),(2147,15,390,2036),(2148,15,390,2038),(2149,15,390,2041),(2150,15,390,2043),(2151,15,390,2045),
(2152,15,390,2049),(2153,15,390,2052),(2154,15,390,1327),(2155,15,390,2056),(2156,15,390,2058),(2157,15,390,2062),(2158,15,390,2065),(2159,15,391,2067),(2160,15,391,2070),(2161,15,391,2073),(2162,15,391,2074),
(2163,15,391,2076),(2164,15,392,2078),(2165,15,392,2080),(2166,15,392,2081),(2167,15,392,2083),(2168,15,392,2085),(2169,15,392,2086),(2170,15,392,2090),(2171,15,395,2092),(2172,15,395,2094),(2173,15,395,2096),
(2174,15,395,2099),(2175,15,395,1153),(2176,15,395,2103),(2177,15,395,2105),(2178,15,18,2109),(2179,15,18,2113),(2180,15,18,2117),(2181,15,18,2119),(2182,15,18,2123),(2183,15,396,170),(2184,15,396,2127),
(2185,15,396,2130),(2186,15,396,2132),(2187,15,397,2135),(2188,15,397,875),(2189,15,397,2139),(2190,15,397,2141),(2191,15,397,2142),(2192,15,397,2145),(2193,15,397,2146),(2194,15,397,2147),(2195,15,398,2151),
(2196,15,398,2154),(2197,15,398,2436),(2198,15,398,2157),(2199,15,398,2159),(2200,15,398,2161),(2201,15,399,2163),(2202,15,399,2165),(2203,15,399,2167),(2204,15,400,2169),(2205,15,400,2172),(2206,15,400,2173),
(2207,15,400,2175),(2208,15,400,2437),(2209,15,401,2179),(2210,15,401,2181),(2211,15,401,2183),(2212,15,401,2184),(2213,15,401,2187),(2214,15,401,168),(2215,15,401,2189),(2216,16,402,2192),(2217,16,402,2193),
(2218,16,402,2195),(2219,16,403,2197),(2220,16,403,2198),(2221,16,403,2200),(2222,16,403,2202),(2223,16,403,2205),(2224,16,403,2206),(2225,16,404,2209),(2226,16,404,2212),(2227,16,404,2214),(2228,16,404,2216),
(2229,16,404,2217),(2230,16,404,2219),(2231,16,405,2220),(2232,16,405,500),(2233,16,405,2224),(2234,16,405,2225),(2235,16,405,2226),(2236,16,405,2228),(2237,16,406,2233),(2238,16,406,2235),(2239,16,406,2237),
(2240,16,406,2239),(2241,16,406,2240),(2242,16,406,2241),(2243,16,407,2242),(2244,16,407,2243),(2245,16,407,2244),(2246,16,407,2245),(2247,16,407,2246),(2248,16,407,2247),(2249,16,407,2248),(2250,16,407,2249),
(2251,16,407,2250),(2252,16,408,2251),(2253,16,408,2252),(2254,16,408,2253),(2255,16,408,2254),(2256,16,408,2255),(2257,16,408,2256),(2258,16,409,2257),(2259,16,409,2258),
(2260,16,409,2259),(2261,16,409,2260),(2262,16,409,2261),(2263,16,409,2262),(2264,16,410,2263),(2265,16,411,2264),(2266,16,411,2265),(2267,16,411,2266),(2268,16,411,2267),(2269,16,411,2268),(2270,16,411,2269),
(2271,16,411,2270),(2272,16,411,2271),(2273,16,412,875),(2274,16,412,2273),(2275,16,412,2274),(2276,16,412,2275),(2277,16,412,2276),(2278,16,413,2277),(2279,16,413,2279),(2280,16,413,795),(2281,16,413,2281),
(2282,16,413,2282),(2283,16,414,2283),(2284,16,414,2284),(2285,16,414,2285),(2286,16,414,2286),(2287,16,415,2287),(2288,16,415,2288),(2289,16,415,2289),(2290,16,415,2290),(2291,16,415,2291),(2292,16,415,2292),
(2293,16,416,2293),(2294,16,416,2294),(2295,16,416,2295),(2296,16,416,617),(2297,16,417,2297),(2298,16,417,2298),(2299,16,417,2299),(2300,16,417,2300),
(2301,16,417,2301),(2302,16,417,2302),(2303,16,417,2303),(2304,16,417,2304),(2305,16,417,2305),(2306,16,418,2308),(2307,16,419,2309),(2308,16,419,2310),(2309,16,419,2311),(2310,16,419,2312),(2311,16,419,2313),
(2312,16,420,697),(2313,16,420,2315),(2314,16,420,2316),(2315,16,420,2317),(2316,16,420,2318),(2317,16,421,2319),(2318,16,421,2320),(2319,16,422,2321),(2320,16,422,2322),(2321,16,422,2323),(2322,16,422,2324);


DROP SEQUENCE IF EXISTS iphistory_id_seq;
DROP TABLE IF EXISTS iphistory CASCADE;
CREATE SEQUENCE iphistory_id_seq;
CREATE TABLE iphistory (
    id integer default nextval('iphistory_id_seq'::text) NOT NULL,
    nodeid integer default 0,
    ipaddr bigint default 0 not null ,
    ipaddr_pub bigint default 0 not null,
    ownerid integer default NULL ,
    netdev integer default NULL ,
    fromdate integer DEFAULT 0,
    todate integer DEFAULT 0,
    PRIMARY KEY (id)
);
CREATE INDEX iphistory_nodeid_idx ON iphistory (nodeid);

/* ---------------------------------------------------
 Aggregates
------------------------------------------------------*/
DROP AGGREGATE IF EXISTS array_agg(anyelement);
CREATE AGGREGATE array_agg (
    BASETYPE=anyelement,
	SFUNC=array_append,
	STYPE=anyarray,
	INITCOND='{}'
);

/* ---------------------------------------------------
 Functions and Views
------------------------------------------------------*/
CREATE OR REPLACE FUNCTION lms_current_user() RETURNS integer AS '
SELECT 
CASE 
    WHEN current_setting(''lms.current_user'') = '''' 
    THEN 0 
    ELSE current_setting(''lms.current_user'')::integer
END
' LANGUAGE SQL;

CREATE VIEW customersview AS
SELECT c.* FROM customers c
        WHERE NOT EXISTS (
	        SELECT 1 FROM customerassignments a 
	        JOIN excludedgroups e ON (a.customergroupid = e.customergroupid) 
	        WHERE e.userid = lms_current_user() AND a.customerid = c.id) 
	        AND c.type IN ('0','1');

CREATE VIEW contractorview AS
SELECT c.* FROM customers c
        WHERE c.type = '2' ;

CREATE OR REPLACE FUNCTION int2txt(bigint) RETURNS text AS $$
SELECT $1::text;
$$ LANGUAGE SQL IMMUTABLE;

CREATE VIEW nas AS
SELECT n.id, inet_ntoa(n.ipaddr) AS nasname, d.shortname, d.nastype AS type,
	d.clients AS ports, d.secret, d.community, d.description
	FROM nodes n
	JOIN netdevices d ON (n.netdev = d.id)
	WHERE n.nas = 1;

CREATE VIEW vnodes AS
SELECT n.*, m.mac
	FROM nodes n
	LEFT JOIN (SELECT nodeid, array_to_string(array_agg(mac), ',') AS mac
		FROM macs GROUP BY nodeid) m ON (n.id = m.nodeid);

CREATE VIEW vmacs AS
SELECT n.*, m.mac, m.id AS macid
    FROM nodes n
    JOIN macs m ON (n.id = m.nodeid);


CREATE VIEW teryt_terc AS
SELECT ident AS woj, 0::text AS pow, 0::text AS gmi, 0 AS rodz,
        UPPER(name) AS nazwa
    FROM location_states
    UNION
    SELECT s.ident AS woj, d.ident AS pow, 0::text AS gmi, 0 AS rodz,
        d.name AS nazwa
    FROM location_districts d
    JOIN location_states s ON (d.stateid = s.id)
    UNION
    SELECT s.ident AS woj, d.ident AS pow, b.ident AS gmi, b.type AS rodz,
        b.name AS nazwa
    FROM location_boroughs b
    JOIN location_districts d ON (b.districtid = d.id)
    JOIN location_states s ON (d.stateid = s.id);

CREATE VIEW teryt_simc AS
SELECT s.ident AS woj, d.ident AS pow, b.ident AS gmi, b.type AS rodz_gmi,
        c.ident AS sym, c.name AS nazwa,
        (CASE WHEN cc.ident IS NOT NULL THEN cc.ident ELSE c.ident END) AS sympod
    FROM location_cities c
    JOIN location_boroughs b ON (c.boroughid = b.id)
    JOIN location_districts d ON (b.districtid = d.id)
    JOIN location_states s ON (d.stateid = s.id)
    LEFT JOIN location_cities cc ON (c.cityid = cc.id);

CREATE VIEW teryt_ulic AS
SELECT st.ident AS woj, d.ident AS pow, b.ident AS gmi, b.type AS rodz_gmi,
        c.ident AS sym, s.ident AS sym_ul, s.name AS nazwa_1, s.name2 AS nazwa_2, t.name AS cecha, s.id
    FROM location_streets s
    JOIN location_street_types t ON (s.typeid = t.id)
    JOIN location_cities c ON (s.cityid = c.id)
    JOIN location_boroughs b ON (c.boroughid = b.id)
    JOIN location_districts d ON (b.districtid = d.id)
    JOIN location_states st ON (d.stateid = st.id);
    

CREATE VIEW monit_vnodes AS 
SELECT m.id AS id, m.test_type, m.test_port, m.send_timeout, m.send_ptime, inet_ntoa(n.ipaddr) AS ipaddr, m.maxptime, 
     COALESCE((SELECT 1 FROM nodes WHERE nodes.id = m.id AND nodes.netdev != 0),0) AS netdev, n.name 
     FROM monitnodes m 
     JOIN nodes n ON (n.id = m.id) 
     WHERE m.active = 1 AND n.ipaddr !=0 ;


/* ---------------------------------------------------
 Data records
------------------------------------------------------*/
INSERT INTO rtcategories (name, description) VALUES ('default', 'default category');
INSERT INTO uiconfig (section, var) VALUES ('userpanel', 'data_consent_text');
INSERT INTO uiconfig (section, var, value, description, disabled) VALUES ('userpanel', 'disable_transferform', '0', '', 0);
INSERT INTO uiconfig (section, var, value, description, disabled) VALUES ('userpanel', 'disable_invoices', '0', '', 0);
INSERT INTO uiconfig (section, var, value, description, disabled) VALUES ('userpanel', 'invoice_duplicate', '0', '', 0);
INSERT INTO uiconfig (section, var, value) VALUES ('userpanel', 'show_tariffname', '1');
INSERT INTO uiconfig (section, var, value) VALUES ('userpanel', 'show_speeds', '1');
INSERT INTO uiconfig (section, var, value, description, disabled) VALUES ('userpanel', 'default_queue', '1', '', 0);
INSERT INTO uiconfig (section, var, value, description, disabled) VALUES ('userpanel', 'default_userid', '0', '', 0);
INSERT INTO uiconfig (section, var, value, description, disabled) VALUES ('userpanel', 'debug_email', '', '', 0);
INSERT INTO uiconfig (section, var, value, description, disabled) VALUES ('userpanel', 'lms_url', '', '', 0);
INSERT INTO uiconfig (section, var, value, description, disabled) VALUES ('userpanel', 'hide_nodesbox', '0', '', 0);
INSERT INTO uiconfig (section, var, value, description, disabled) VALUES ('userpanel', 'logout_url', '', '', 0);
INSERT INTO uiconfig (section, var, value, description, disabled) VALUES ('userpanel', 'owner_stats', '0', '', 0);
INSERT INTO uiconfig (section, var, value) VALUES ('userpanel', 'default_categories', (SELECT MAX(id) FROM rtcategories));
INSERT INTO uiconfig (section,var,value) VALUES ('monit','active_monitoring','1');
INSERT INTO uiconfig (section,var,value) VALUES ('monit','netdev_clear','365');
INSERT INTO uiconfig (section,var,value) VALUES ('monit','netdev_test','1');
INSERT INTO uiconfig (section,var,value) VALUES ('monit','netdev_test_type','icmp');
INSERT INTO uiconfig (section,var,value) VALUES ('monit','nodes_clear','365');
INSERT INTO uiconfig (section,var,value) VALUES ('monit','nodes_test','1');
INSERT INTO uiconfig (section,var,value) VALUES ('monit','nodes_test_type','icmp');
INSERT INTO uiconfig (section,var,value) VALUES ('monit','packetsize','32');
INSERT INTO uiconfig (section,var,value) VALUES ('monit','test_script_dir','/usr/local/sbin/lms-monitoring.pl');
INSERT INTO uiconfig (section,var,value) VALUES ('monit','step_test_netdev','5');
INSERT INTO uiconfig (section,var,value) VALUES ('monit','step_test_nodes','5');
INSERT INTO uiconfig (section,var,value) VALUES ('monit','step_test_owner','5');
INSERT INTO uiconfig (section,var,value) VALUES ('monit','onwer_clear','365');
INSERT INTO uiconfig (section,var,value) VALUES ('monit','owner_test','1');
INSERT INTO uiconfig (section,var,value) VALUES ('monit','owner_test_type','icmp');
INSERT INTO uiconfig (section,var,value) VALUES ('phpui','default_module','welcome_new');
INSERT INTO uiconfig (section,var,value) VALUES ('phpui','callcenter_pagelimit','50');
INSERT INTO uiconfig (section,var,value) VALUES ('hiperus_c5','numberplanid','');
INSERT INTO uiconfig (section,var,value) VALUES ('hiperus_c5','taxrate','');
INSERT INTO uiconfig (section,var,value) VALUES ('hiperus_c5','prodid','');
INSERT INTO uiconfig (section,var,value) VALUES ('hiperus_c5','content','szt');
INSERT INTO uiconfig (section,var,value) VALUES ('hiperus_c5','wlr','0');
INSERT INTO uiconfig (section,var,value) VALUES ('hiperus_c5','leftmonth','1');
INSERT INTO uiconfig (section,var,value) VALUES ('hiperus_c5','accountlist_pagelimit','50');
INSERT INTO uiconfig (section,var,value) VALUES ('hiperus_c5','terminallist_pagelimit','50');
INSERT INTO uiconfig (section,var,value) VALUES ('hiperus_c5','force_relationship','1');
INSERT INTO uiconfig (section,var,value) VALUES ('hiperus_c5','number_manually','0');
INSERT INTO uiconfig (section,var,value) VALUES ('hiperus_c5','lms_login','');
INSERT INTO uiconfig (section,var,value) VALUES ('hiperus_c5','lms_pass','');
INSERT INTO uiconfig (section,var,value) VALUES ('hiperus_c5','lms_url','http://localhost/lms');
INSERT INTO uiconfig (section,var,value) VALUES ('phpui','delete_link_in_customerbalancebox','0');
INSERT INTO uiconfig (section,var,value) VALUES ('phpui','config_empty_value','0');
INSERT INTO uiconfig (section,var,value) VALUES ('phpui','installation_name','');
INSERT INTO uiconfig (section,var,value) VALUES ('phpui','iphistory','1');
INSERT INTO uiconfig (section,var,value) VALUES ('phpui','iphistory_pagelimit','50');

INSERT INTO up_rights(module, name, description) VALUES ('info', 'edit_addr_ack', 'Customer can change address information with admin acknowlegment');
INSERT INTO up_rights(module, name, description) VALUES ('info', 'edit_addr', 'Customer can change address information');
INSERT INTO up_rights(module, name, description, setdefault) VALUES ('info', 'edit_contact_ack', 'Customer can change contact information with admin acknowlegment', 0);
INSERT INTO up_rights(module, name, description) VALUES ('info', 'edit_contact', 'Customer can change contact information');

INSERT INTO countries (name) VALUES ('Lithuania');
INSERT INTO countries (name) VALUES ('Poland');
INSERT INTO countries (name) VALUES ('Romania');
INSERT INTO countries (name) VALUES ('Slovakia');
INSERT INTO countries (name) VALUES ('USA');

INSERT INTO nastypes (name) VALUES ('mikrotik_snmp');
INSERT INTO nastypes (name) VALUES ('cisco');
INSERT INTO nastypes (name) VALUES ('computone');
INSERT INTO nastypes (name) VALUES ('livingston');
INSERT INTO nastypes (name) VALUES ('max40xx');
INSERT INTO nastypes (name) VALUES ('multitech');
INSERT INTO nastypes (name) VALUES ('netserver');
INSERT INTO nastypes (name) VALUES ('pathras');
INSERT INTO nastypes (name) VALUES ('patton');
INSERT INTO nastypes (name) VALUES ('portslave');
INSERT INTO nastypes (name) VALUES ('tc');
INSERT INTO nastypes (name) VALUES ('usrhiper');
INSERT INTO nastypes (name) VALUES ('other');
INSERT INTO nastypes (name) VALUES ('mikrotik_api');
INSERT INTO nastypes (name) VALUES ('ubiquiti_snmp');

INSERT INTO dbinfo (keytype, keyvalue) VALUES ('dbversion', '2012111100');
INSERT INTO dbinfo (keytype, keyvalue) VALUES ('dbvex', '2013010300');
