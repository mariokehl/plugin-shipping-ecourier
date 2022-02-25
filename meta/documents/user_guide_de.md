# Was ist eCourier? Wofür?

Der eCourier ist das Allroundsystem für KEP-Dienstleister (Kurier-Express-Paket) von [bamboo software](https://bamboo-software.de/). Dieses Plugin ermöglicht dir als All-in-One Lösung mit mehr als 60 Kurierdiensten, egal ob Stadt-/Direktkurier oder national und international Expressversand, zu versenden.

Ein Kurierdienst eignet sich hervorragend für zeitkritische oder hochwertige Warensendungen. Klassischer Anwendungsfall ist z.B. der Versand von tiefkühlpflichtigen Lebensmitteln über Nacht, so dass die Kühlkette nicht unterbrochen wird.

Verwende dieses Plugin, um einen der unterstützten Kurierdienste in deinem plentymarkets System zu integrieren. Danach ist es möglich, den bekannten Arbeitsschritt der Versandauftragsanmeldung im Versand-Center sowie in plentyBase durchzuführen. 

## Quickstart

Um dieses Plugin zu benutzen, musst du als Versender bei dem Kurierdienst deiner Wahl registriert sein. Du erhälst danach Benutzername und Passwort zur Konfiguration des Plugins.

Eine Auflistung der unterstützten Kurierdienste findest du [hier](https://bamboo-software.de/ecourier/).

## Praxisbeispiel: DER KURIER

**Nutze für deine Registrierung bei DER KURIER einen der folgenden Wege:**

- Telefon: +49 (0) 6677 95-0
- [E-Mail](mailto:info@derkurier.de)
- [Kontaktformular](https://derkurier.de/kontakt/)

Bitte erwähne bei deiner Kontaktaufnahme, dass du das plentymarkets Plugin für eCourier hier im Marketplace gefunden hast.

### Plugin-Konfiguration

Sobald dir die Benutzerdaten von DER KURIER vorliegen, kannst du diese im Plugin hinterlegen und dein erstes Versandetikett generieren.

#### Zugangsdaten hinterlegen

Für deinen Einstieg musst du zunächst den API-Zugriff ermöglichen.

1. Öffne das Menü **Plugins » Plugin-Set-Übersicht**.
2. Wähle das gewünschte Plugin-Set aus.
3. Klicke auf **eCourier (bamboo software)**.<br>→ Eine neue Ansicht öffnet sich.
4. Wähle den Bereich **Allgemein** aus der Liste.
5. Trage deinen Benutzernamen und dein Passwort ein.
6. **Speichere** die Einstellungen.

Achte darauf, dass für alle Testszenarien der Modus auf **DEMO** steht. Du kannst nach Anpassung der Versendereinstellungen im Versand-Center Sendungen anmelden und erhälst die passende Transaktions-Nr. inkl. Label zurück.

Sobald du von DER KURIER die Freigabe für den Produktivbetrieb erhalten hast, musst du hier den Schalter auf **LIVE** stellen.

#### Versendereinstellungen

Hinterlege im Bereich **Absender** deine Adressdaten gemäß Registrierung. Zusätzlich kannst du unter **Versand** deine Abhol-/Zustellzeit und optionale Zustellhinweise konfigurieren.

### DER KURIER als Versandoption

Wenn das Plugin erfolgreich installiert und die Tests erfolgreich verlaufen sind, ist es an der Zeit den Versanddienstleister als Option im Checkout deines Shops auswählbar zu machen.

1. Aktiviere deine **[Lieferländer](https://knowledge.plentymarkets.com/fulfillment/versand-vorbereiten#100)**
2. Erstelle deine (Versand-)**[Regionen](https://knowledge.plentymarkets.com/fulfillment/versand-vorbereiten#400)**
3. Erstelle deinen **[Versanddienstleister](https://knowledge.plentymarkets.com/fulfillment/versand-vorbereiten#800)** _**DER KURIER**_
  * Wähle _**DER KURIER**_ in der Spalte _Versanddienstleister_ aus
  * Hinterlege `https://leotrace.derkurier.de/paketstatusNeu.aspx?Lang=DE&parcel=$PaketNr&ZIP=$PLZ` als Tracking-URL
4. Erstelle deine **[Versandprofile](https://knowledge.plentymarkets.com/fulfillment/versand-vorbereiten#1000)** und **[Portotabellen](https://knowledge.plentymarkets.com/fulfillment/versand-vorbereiten#1500)** für _**DER KURIER**_

#### DSGVO: Informationen zur Datenübermittlung (E-Mail und Telefon)

Du kannst in deinem Versandprofil über die Option **[E-Mail und Telefon übertragen](https://knowledge.plentymarkets.com/business-entscheidungen/rechtliches/dsgvo#700)** diesbezüglich Konfigurationen vornehmen. Die E-Mail-Adresse und Telefonnummer des Kunden sind in der Schnittstelle kein Pflichtfeld. Du musst diese also nicht unbedingt mit übertragen.

## Credits

Dieses Plugin wurde mit freundlicher Unterstützung von [DER KURIER](https://derkurier.de/) und [beefgourmet.de](https://www.beefgourmet.de/) finanziert.

<sub><sup>Jeder einzelne Kauf hilft bei der ständigen Weiterentwicklung und der Umsetzung von Userwünschen. Vielen Dank!</sup></sub>
