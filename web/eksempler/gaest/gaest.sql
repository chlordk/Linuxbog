-- $Id$
-- G�stebog
-- af Hans Schou

-- Installeres med kommandoen:
--  psql nobody < gaest.sql

--DROP TABLE gaest;
--DROP SEQUENCE gaest_gid_seq;
--DROP INDEX gaest_opdat;

CREATE TABLE gaest(
  gid     serial,
  opdat   timestamp NOT NULL DEFAULT 'now',
  vises   bool NOT NULL DEFAULT 'f',
  navn    text NOT NULL,
  email   text,
  hilsen  text
);

CREATE INDEX gaest_opdat ON gaest(opdat);

COMMENT ON TABLE gaest IS 'G�stebogen er et eksempel til b�gerne Friheden til at v�lge.
Med kun een tabel er dette en lille simpel demonstration af hvordan man kan bruge
databaser sammen med web.';

COMMENT ON COLUMN gaest.gid IS 'G�stID er et unikt ID for hver record';
COMMENT ON COLUMN gaest.opdat IS 'Automatisk variabel der bliver opdateret ved �ndringer.';
COMMENT ON COLUMN gaest.vises IS 'Kun godkendte skrivninger i g�stebogen skal vises.
Administrator kan nemt finde dem der ikke vises, og dern�st validere dem.';
COMMENT ON COLUMN gaest.navn IS 'Navnet p� den der har skrevet sig ind i g�stebogen.
F.eks. "Jens Jensen"';
COMMENT ON COLUMN gaest.email IS 'E-mail adresse';
COMMENT ON COLUMN gaest.hilsen IS 'En hilsen fra personen';

COMMENT ON INDEX gaest_opdat IS 'Et index der g�r det hurtigere at sortere p� dato.';

INSERT INTO gaest(vises,navn,email,hilsen)
VALUES('t', 'Mig Selv', 'joe@aol.com', 'Hej mig selv, s� er vi igang');

SELECT * FROM gaest WHERE vises ORDER BY opdat DESC;

-- EOF
