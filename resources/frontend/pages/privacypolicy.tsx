import { Link } from "react-router-dom";

const locale = {
    title: "A Budapest V. kerületi Eötvös József Gimnázium Diákönkormányzat honlapjának Adatkezelési Tájékoztatója",
    date: "2023 október",
    laws: {
        title: "Az adatkezelésre vonatkozó főbb jogszabályok",
        array: [
            "A természetes személyeknek a személyes adatok kezelése tekintetében történő védelméről és az ilyen adatok szabad áramlásáról, valamint a 95/46/EK rendelet hatályon kívül helyezéséről szóló 2016. április 27-ei 2016/679 Európai Parlamenti és Tanácsi (EU) rendelet (a továbbiakban: GDPR),",
            "Az információs önrendelkezési jogról és az információszabadságról szóló 2011. évi CXII. törvény (a továbbiakban: Infotv.),",
            "Az elektronikus kereskedelmi szolgáltatások, valamint az információs társadalommal összefüggő szolgáltatások egyes kérdéseiről szóló 2001. évi CVIII. törvény (Ektv.), és",
            "A nemzeti köznevelésről szóló 2011. évi CXC. törvény (a továbbiakban: Nkt.)",
        ],
    },
    participants: {
        title: "Az adatkezelés résztvevői",
        handler: {
            title: "Az adatkezelő",
            headers: {
                name: "Név",
                address: "Székhely",
                phone: "Telefon",
                email: "Elektronikus levelezőcím",
                website: "Honlap",
                activity_location: "Az adatkezelés helye",
                storageprovider: "Tárhelyszolgáltató",
            },
            values: {
                name: "Eötvös József Gimnázium",
                address: "1053 Budapest, Reáltanoda utca 7.",
                phone: "",
                email: "dev.do@e5vos.hu",
                website: "https://e5vosdo.hu",
                activity_location:
                    "Eschborner Landstraße 100, 60489 Frankfurt am Main, Németország",
                storageprovider:
                    "AWS EMEA SARL, 38 Avenue John F. Kennedy, L-1855, Luxembourg",
            },
            description:
                "Az Adatkezelő az a személy, aki önállóan vagy másokkal együtt az adat kezelésének célját meghatározza, az adatkezelésre (beleértve a felhasznált eszközt) vonatkozó döntéseket meghozza és végrehajtja, vagy az adatfeldolgozóval végrehajtatja. Az Adatkezelő felel a 5. pontban kifejtett Érintett jogainak érvényesülésért, illetve az adatkezelés megfelelő biztonságáért.",
        },
        processor: {
            title: "Az adatfeldolgozó",
            description:
                "Az adatfeldolgozó az a személy, aki az Adatkezelő megbízásából vagy rendelkezése alapján személyes adatokat kezel, műveleteket hajt végre. Az adatfeldolgozó továbbá megfelelő garanciákat nyújt az adatkezelés jogszerűségének és az Érintett jogai védelmének biztosítására alkalmas műszaki és szervezési intézkedések végrehajtására. A jelen tájékoztató tárgyába tartozó adatkezelésből történő adatfeldolgozást is az Adatkezelő végzi.",
        },
        owner: {
            title: "Az érintett",
            description:
                "Az Érintett egy bármely információ (jelen esetben bizonyos személyes adatok) alapján azonosítható személy. A jelen tájékoztató tárgyába tartozó Érintett az, aki rendelkezik a Budapest V. kerületi Eötvös József Gimnázium (a továbbiakban: Intézmény) által a vele valamilyen jogállásban lévő személyeknek (tanárok, diákok) biztosított egyedei „e5vos.hu” domain névvel végződő elektronikus levelezőcímmel.",
        },
    },
    handling: {
        title: "Az adatkezelés",
        principles: {
            title: "Az adatkezelés elvei",
            items: [
                {
                    title: "Jogszerűség és átláthatóság",
                    description:
                        "Személyes adat kizárólag az Érintett számára is egyértelműen meghatározott, tisztességes módon, jogszerű célból, a jog gyakorlása és kötelezettség teljesítése érdekében kezelhető. Az adatkezelésnek minden szakaszában meg kell felelnie az adatkezelés céljának, az adatok gyűjtésének és kezelésének tisztességesnek és törvényesnek kell lennie. Az adatkezelés továbbá összhangban a Nemzeti Adatvédelmi és Információszabadság Hatóság (a továbbiakban: Hatóság) irányelveivel. Az Adatkezelő az Érintett személyes adataival kapcsolatban a lehető leggyakrabban tájékoztatja az Érintettet, vagy annak kérelmeire választ ad.",
                },
                {
                    title: "Célhoz kötöttség",
                    description:
                        "Csak olyan személyes adat kezelhető, amely az adatkezelés céljának megvalósulásához elengedhetetlen, a cél elérésére alkalmas. A személyes adat csak a cél megvalósulásához szükséges mértékben és ideig kezelhető.",
                },
                {
                    title: "Adattárolás",
                    description:
                        "Az adatkezelés során arra alkalmas műszaki vagy szervezési intézkedések alkalmazásával biztosítani kell a személyes adatok megfelelő biztonságát, az adatok jogosulatlan vagy jogellenes kezelésével, véletlen elvesztésével, megsemmisítésével vagy károsodásával szembeni védelmet is ideértve. Az adatkezelésnek céljai szempontjából megfelelőnek és relevánsak kell lennie, az adattakarékosságra kell törekedni. Az adattárolásának olyan formában kell történnie, amely az Érintett azonosítását csak a személyes adatok kezelése céljainak eléréséhez szükséges ideig teszi lehetővé.",
                },
                {
                    title: "Pontosság",
                    description:
                        " Az adatkezelés során biztosítani kell az adatok pontosságát, teljességét és naprakészségét, valamint azt, hogy az Érintettet csak az adatkezelés céljához szükséges ideig lehessen azonosítani. Minden észszerű intézkedést meg kell tenni annak érdekében, hogy az adatkezelés céljai szempontjából pontatlan személyes adatokat haladéktalanul töröljék vagy helyesbítsék. A személyes adat az adatkezelés során mindaddig megőrzi minőségét, amíg kapcsolata az Érintettel helyreállítható. ",
                },
            ],
            accountability:
                "Az Adatkezelőnek az összes alapelv betartásáról el kell tudni számolnia minden körülmény között.",
        },
        basis: {
            title: "Az adatkezelés jogalapja",
            description:
                "A GDPR 6. cikk (1) bekezdésének a) pontja alapján az Érintett a honlapra történő belépéskor a „Log in with Google / Bejelentkezés Google-fiókkal” ikon jóváhagyásával, a bejelentkezéskor történő személyes adatainak megadásával járul hozzá az adatkezeléshez és az ahhoz kapcsolódó műveletekhez. Az adatkezelés csak e jogalap fennállásáig történhet. A honlap nem látogatható a 3.4 pontban kifejtett személyes adatok megadása nélkül. ",
        },
        goal: {
            title: "Az adatkezelés célja",
            description:
                "Az Adatkezelő az adatkezelést elsősorban az Intézmény adminisztrációs segítésére végzi. Az adatkezelés az Intézmény programjai lebonyolításához szükséges, hogy a résztvevők igényfelmérését és a jelentkezés alapú programokra történő elosztást a honlapon keresztül segítse (a továbbiakban együtt: jelentkezési adat). Az adatkezelést ebből kifolyólag statisztikai célból is végzi az Adatkezelő, hogy az Intézmény láthassa az egyes programok sikerességét, illetve, hogy az egyes osztályok osztályfőnökei láthassák diákjaik aktivitását.",
        },
        tech: {
            title: "Az adatkezelés technikai lebonyolítása",
            description:
                "Jelen adatkezelési tájékoztató tárgyába tartozó adatkezelés profilalkotó és automatizált. Ez azt jelenti, hogy az Érintett személyes adatait automatizált rendszer a jelentkezési adatai mellé párosítja, így készül a 3.3 pontban kifejtett statisztika. Az automatizált adatkezelés nem korlátozhatja az Érintett személyiségi, véleménynyilvánítás szabadságához fűződő és a tájékozódáshoz való jogát.",
            sourcesIntro: "Az adatkezelés gyűjtésének forrásai az Érintett",
            sources: [
                "neve,",
                "az Intézmény által a vele valamilyen jogviszonyban álló személyeknek biztosított egyedei „e5vos.hu” domain névvel végződő elektronikus levelezőcíme,",
                "az Intézmény által a vele tanulói jogviszonyban lévő személyeknek biztosított egyedi kódja (diákkód) - mely a tanuló első tanulói éve, osztályának betűjele, osztálynévsori sorszáma, az Intézmény rövid neve -EJG -, és a tanuló OM-azonosítójának utolsó három számjegyéből áll -, illetve",
                "a jelentkezési adatai.",
            ],
            location:
                "Az Adatkezelő az Érintett személyes adatait az Európai Unión (EU) és az Európai Gazdasági Térségen (EGT) belül tárolja. Mivel az adatkezelés tényleges helyszíne a 2.1 pontban feltüntetett helyszínen van, így az Érintett személyes adatai adattovábbításra kerülnek egy EGT-n belüli államba. Adattovábbítás harmadik országba (EGT-n kívüli ország) vagy nemzetközi szervezet részére nem történik.",
        },
        access: {
            title: "Az adatok megismerésére jogosultak",
            intro: "",
            accessors: [
                "Diákönkormányzata,",
                "valamilyen jogállásban lévő tanára, illetve",
                "intézményvezetése fér hozzá.",
            ],
            location:
                "Adattovábbítás a 3.4 pontban említett tényleges adatkezelési helyszínen kívül az imént felsorolt harmadik személyeknek történik a honlapról az Intézményen belül.",
        },
        duration: {
            title: "Az adatkezelés időtartama",
            description:
                "Az Érintett személyes adatait az Adatkezelő az adatkezelés kezdeti időpontjakor aktuális tanév végéig kezeli. Tanévnek az Nkt. 4. § 30. pontjában meghatározott szeptember 1-jétől a következő év augusztus 31-éig tartó időszakot kell tekinteni. Az adatkezelés a tanév során bármikor létrejöhet. Az Adatkezelő legkorábban a nyári tanítási szünet első napján, legkésőbb a tanév utolsó napján adattörlést végez az Érintett addig tárolt adatairól és profiljáról.",
        },
    },
    miscellaneous: {
        title: "Egyéb tájékoztatások",
        securityMeasures: {
            title: "Adatbiztonsági kötelezettségek és intézkedések",
            intro: "Az Adatkezelő kötelezi magát arra, hogy gondoskodik az általa kezelt személyes adatok biztonságáról, és ezt betartassa az adatokhoz való hozzáférést biztosított a 3.5 pontban felsorolt személyekkel. Az Adatkezelő a kockázat mértékének megfelelő szintű adatbiztonságot garantálja. A biztonság megfelelő szintjének meghatározásakor figyelembe veszi az adatkezelésből eredő olyan kockázatokat, amelyek a kezelt személyes adatok ",
            sources: [
                "véletlen vagy jogellenes megsemmisítéséből, ",
                "elvesztéséből, ",
                "megváltoztatásából, ",
                "jogosulatlan nyilvánosságra hozatalából, vagy ",
                "az azokhoz való jogosulatlan hozzáférésből erednek. ",
            ],
            securityGoals:
                "Az Adatkezelő célja egyrészt a 3.1 pontban kifejtett elvek megvalósítása, másrészt az Érintett jogainak védelméhez szükséges garanciák beépítése az adatkezelés folyamatába.",
            techMeasuresIntro:
                "Az Adatkezelő megfelelő technikai és szervezési intézkedéseket hajt végre annak biztosítására, hogy",
            techMeasures: [
                "kizárólag olyan személyes adatok kezelésére kerüljön sor, amelyek a 3. pontban kifejtett adatkezelési célok, a kezelt személyes adatok mennyisége, kezelésük mértéke, tárolásuk időtartama és hozzáférhetőségük szempontjából szükségesek, ",
                "utólag ellenőrizhető és megállapítható legyen, hogy mely személyes adatokat, mely időpontban, ki vitt be az adatkezelő rendszerbe,",
                "megtagadja az adatkezelési rendszer jogosulatlan személyek általi hozzáférését,",
                "megakadályozza az adathordozók jogosulatlan olvasását, másolását, módosítását vagy eltávolítását,",
                "a személyes adatok továbbítása során vagy az adathordozó szállítása közben történő jogosulatlan megismerést, másolást, módosítást vagy törlést megakadályozza, ",
                "kialakítsa a fizikai vagy műszaki incidens esetén az arra való képességet, hogy a személyes adatokhoz való hozzáférést és az adatok rendelkezésre állását kellő időben vissza lehessen állítani és az Érintettet az incidensről tájékoztatni, ",
                "az adatkezelő rendszer működőképes legyen, a működése során fellépő hibákról jelentés készüljön, ",
                "létrehozza az adatkezelő rendszerek bizalmas jellegét, rendelkezésre állását és ellenálló képességét, és",
                "üzemzavar esetén az adatkezelő rendszer helyreállítható legyen.",
            ],
            circumstancesIntro:
                "Az intézkedések kialakítása és végrehajtása során az Adatkezelő figyelembe veszi az adatkezelés összes körülményét, így különösen",
            circumstances: [
                "a tudomány és a technológia mindenkori állását,",
                "az adatkezelés jellegét, hatókörét és céljait, ",
                "az Érintettek jogainak érvényesülésére az adatkezelés által jelentett változó valószínűségű és súlyosságú kockázatokat. ",
            ],
            methods:
                "Továbbá az Adatkezelő eljárást dolgoz ki az adatkezelés biztonságának garantálására hozott technikai és szervezési intézkedések hatékonyságának rendszeres tesztelésére, felmérésére és értékelésére.",
            policy: "Az Adatkezelő kialakítja azokat az eljárási szabályokat, amelyek biztosítják, hogy a kezelt adatok védettek legyenek, illetőleg megakadályozza azok megsemmisülését, jogosulatlan felhasználását és jogosulatlan megváltoztatását. A kezelt személyes adatok jelszóval védett adatbázisban tárolódnak, illetve az informatikai rendszerek biztonsága érdekében az Adatkezelő a külső- és belső adatvesztések megelőzése érdekében víruskereső és vírusirtó programot használ. ",
        },
        cookie: {
            title: "Cookie-k (Sütik) használata",
            description:
                "A sütik (cookie-k) valójában kis, adatot tároló fájlok, amiket a látogató böngészője menedzsel. A látogató a saját böngészőjében engedélyezheti vagy letilthatja a sütik használatát, törölheti a korábban elhelyezett sütiket.",
            cookiesIntro: "Az oldalunk a következő sütiket használja:",
            cookiesHeader: {
                name: "Név",
                duration: "Élettartam",
                description: "Leírás",
                required: "Müködéshez szükséges",
            },
            cookies: [
                {
                    name: "XSRF-TOKEN",
                    duration: "Ismeretlen",
                    description: "CSRF védelmi süti",
                    required: "Szükséges",
                },
                {
                    name: "e5vosdo.hu_session",
                    duration: "Ismeretlen",
                    description: "Munkamenet azonosíto süti",
                    required: "Szükséges",
                },
            ],
        },
    },
    ownersRights: {
        title: "Az érintett jogai",
        rightsListIntro: "Az Érintett rendelkezik",
        rightsList: [
            "előzetes tájékoztatáshoz való joggal,",
            "hozzáféréshez való joggal,",
            "helyesbítéshez való joggal,",
            "törléshez való joggal,",
            "adatkezelés korlátozásához való joggal,",
            "adathordozhatósághoz való joggal, és",
            "tiltakozáshoz való joggal.",
        ],
        access: {
            title: "Hozzáféréshez való jog (GDPR 15. cikk és Infotv. 17. § alapján)",
            description:
                "A hozzáféréshez való jog érvényesülése érdekében az Adatkezelő az Érintettet a 6.1 pontban kifejtett módú írásos kérelmére személyes adatait, az adatkezelés tárgyát képező személyes adatok másolatát rendelkezésére bocsájtja. Továbbá az Adatkezelő az 5.1 pontban kifejtett adatkezeléssel összefüggő információt is az Érintett számára hozzáférhetővé teszi.",
        },
        correction: {
            title: "Helyesbítéshez való jog (GDPR 16. cikk és Infotv. 18. § alapján)",
            description:
                "A helyesbítéshez való jog érvényesülése érdekében az Adatkezelő az általa kezelt pontatlan, helytelen vagy hiányos személyes adatokat pontosítja, vagy helyesbíti. Ezeket az adatkezelési műveleteket az Érintett a 6.1 pontban kifejtett módon írásban is kérelmezheti, továbbá, ha az adatkezelés céljával összeegyeztethető, akkor az Adatkezelőhöz eljuttatott nyilatkozattal a kezelt adatok kiegészítését is kérelmezheti.",
        },
        delete: {
            title: "Törléshez való jog (GDPR 17. cikk és Infotv. 20. § alapján)",
            intro: "A törléshez való jog érvényesítése érdekében az Adatkezelő haladéktalanul törli az Érintett személyes adatait és az esetleges összes másolatot, ha az adatkezelés jogellenes",
            causes: [
                "a 3.1 pont alapelveivel való ellentét,",
                "az adatkezelési cél megszűnése,",
                "jogszabályban meghatározott időtartama letelte, vagy",
                "az adatkezelési jogalap megszűnése, azaz az Érintett hozzájárulásának visszavonása miatt.",
            ],
            description: [
                "Jogellenes adatkezelés esetén az adattörlést jogszabály, a Hatóság vagy a bírósági elrendelése mellett az Érintett is kérelmezheti a 6.1 pontban kifejtett módon. Az Adatkezelő akkor is köteles az adattörlést végrehajtani, ha az Érintett az adatkezeléshez adott hozzájárulását visszavonja, vagy él az 5.7 pontban kifejtett jogával.",
                "Ha jogszabály tiltja a kezelt adatok törlését, akkor az Adatkezelő a kérelmezett vagy elrendelt adattörlés megtagadhatja. A megtagadást csak írásos formában, és az adatkezelésre vonatkozó jogszabály azon pontját megjelölve teheti meg, amelyre hivatkozva tagadja meg az adattörlést. ",
            ],
        },
        limit: {
            title: "Adatkezelés korlátozásához való jog (GDPR 18. cikk és Infotv. 19. § alapján)",
            requestIntro:
                "Az adatkezelés korlátozásához való jog érvényesülése érekében az Adatkezelő korlátozza a kezelt személyes adatok kezelését, ha az Érintett írásban",
            requestTypes: [
                "vitatja a kezelt adatok pontosságát, helytállóságát vagy hiánytalanságát, és ezek egyike sem állapítható meg kétséget kizáróan, ",
                "tiltakozik az 5.7 pontban meghatározottak szerint az adatkezelés ellen, ",
                "igényli azt jogérvényesítési vagy jogorvoslati célból az Adatkezelőnek már szükségtelenül kezelt személyes adataira, vagy",
                "ellenzi a kezelt adatok törlését azok jogellenes kezelése, vagy valamely más jogos érdekének fennállása miatt.",
            ],
            durationIntro:
                "Az adatkorlátozás arra az időtartamra vonatkozik, amíg ",
            durationScopes: [
                "az Érintett által megjelölt indok szükségessé teszi azt, ",
                "a fennálló kétség tisztázásra nem kerül,",
                "kiderül, hogy melyik fél jogos indokai élveznek elsőbbséget, ",
                "a törlés mellőzését megalapozó jogos érdek fennáll, vagy ",
                "a kezelt adatok megőrzése szükséges valamilyen eljárás vagy vizsgálat lefolytatásának lezárásáig.",
            ],
            rights: "Az adatkezelés korlátozásának időtartama alatt a korlátozással érintett személyes adatokkal az Adatkezelő a tároláson túl egyéb adatkezelési műveletet kizárólag az Érintett jogos érdekének érvényesítése céljából vagy jogszabályban meghatározottak szerint végezhet. Az adatkezelési korlátozás megszüntetése esetén az Adatkezelő a korlátozás feloldásáról az Érintettet előzetesen tájékoztatja.",
        },
        portability: {
            title: "Adathordozhatósághoz való jog (GDPR 20. cikk alapján)",
            description:
                "Az adathordozhatósághoz való jog érvényesülése érdekében az Érintett a 6.1 pontban kifejtett módú írásos kérelmére az Adatkezelő a kezelt adatokat az Érintett rendelkezésére bocsájtja széles körben használt, géppel olvasható formátumban. Az Érintett továbbá jogosult arra, hogy a kezelt személyes adatait egy másik adatkezelőnek továbbítsa az Adatkezelő adatkezelésének akadályozása nélkül. Az adatkezeléshez való jog gyakorlása nem sértheti a törléshez való jogot.",
        },
        complain: {
            title: "Tiltakozáshoz való jog (GDPR 21. cikk alapján)",
            description:
                "A tiltakozáshoz való jog érvényesülése érdekében az Érintett a 6.1 pontban kifejtett módú írásos kérelmében a saját helyzetével kapcsolatos okokból bármikor tiltakozhat személyes adatainak kezelése ellen, legfőképpen, ha a személyes adatok kezelése közvetlen üzletszerzés érdekében, vagy profilalkotással történik. Ebben az esetben az Adatkezelő a személyes adatokat nem kezelheti tovább, de tájékoztathatja az Érintettet, hogy milyen következményekkel jár az adatkezelés befejezése.",
        },
    },
    complaint: {
        title: "Az adatkezeléssel kapcsolatos jogérvényesítési, jogorvoslati lehetőségek",
        contact: {
            title: "Az Adatkezelő megkeresése, kérelem benyújtása",
            intro: "Az Érintett az adatkezeléssel kapcsolatban írásban hagyományosan vagy elektronikus úton a 2.1 pontban meghatározott elérhetőségeken keresztül kérelmet intézhet az Adatkezelőhöz, ha",
            causes: [
                "érvényesíteni szeretné a 5. pontban felsorolt valamely jogát,",
                "az adatkezeléssel vagy jelen tájékoztató valamely részével kapcsolatban kérdése, kételye van, vagy felvilágosítást kér, ",
                "panaszt kíván előterjeszteni, vagy",
                "bírósági, illetve hatósági eljárás kezdeményezéséről akarja tájékoztatni az Adatkezelőt.",
            ],
            deadline:
                "Az Érintett kérelmét az Adatkezelő a lehető legrövidebb időn belül, de legfeljebb egy hónapon belül felülvizsgálja, és az Érintettet a felülvizsgálás eredményéről írásban tájékoztatja. A kérelmek számát és összetettségét figyelembe véve ez a határidő további két hónappal meghosszabbítható.",
            cost: "Az Érintett kérelmét az Adatkezelő a lehető legrövidebb időn belül, de legfeljebb egy hónapon belül felülvizsgálja, és az Érintettet a felülvizsgálás eredményéről írásban tájékoztatja. A kérelmek számát és összetettségét figyelembe véve ez a határidő további két hónappal meghosszabbítható.",
            exceptWhen: [
                "ismételten kérelmet nyújt be, ",
                "és e kérelem alapján az Adatkezelő által kezelt adatainak helyesbítését, törlését vagy az adatkezelés korlátozását az Adatkezelő jogszerűen mellőzi, ",
            ],
            costRecovery:
                "az Adatkezelő az Érintett jogainak ismételt és megalapozatlan érvényesítésével összefüggésben közvetlenül felmerült költségeinek megtérítését követelheti az Érintettől, illetve megtagadhatja a kérelem alapján a történő intézkedését.",
            pleaseno:
                "Az Adatkezelő javasolja az Érintettnek, hogy amennyiben bírósági vagy hatósági eljárás kezdeményezését tervezi, előzetesen érdeklődjön az Adatkezelőnél, mivel a felmerült kérdései, orvoslást igénylő kérései tekintetében a szükséges információk az Adatkezelőnél állnak rendelkezésre. ",
            damagesIntro:
                "Az Adatkezelő az Érintett adatainak jogellenes kezelésével vagy az adatbiztonság követelményeinek megszegésével az okozott kárt megtéríti. Az Adatkezelő mentesül a felelősség alól, ha a kár",
            damagesExceptions: [
                "az adatkezelés körén kívül eső elháríthatatlan ok idézte elő, vagy ",
                "a károsult szándékos vagy súlyosan gondatlan magatartásából származott.",
            ],
        },
        courtStart: {
            title: "Bíróság eljárás kezdeményezése:",
            description:
                "Ha az Érintett úgy ítéli meg, hogy az Adatkezelő a személyes adatait a rá vonatkozó főbb jogszabályokban meghatározott előírások megsértésével kezeli, akkor bírósági eljárást kezdeményezhet. A per elbírálása a bíróság hatáskörébe tartozik. A per – az Érintett választása szerint – az Érintett lakóhelye vagy tartózkodási helye szerinti illetékes bíróság előtt is megindítható. ",
        },
        authoritiesStart: {
            title: "Hatósági eljárás kezdeményezése",
            intro: "Ha az Érintett úgy érzi, hogy személyes adatai kezelésekor jogsérelem következett be, vagy annak közvetlen veszélye áll fenn, akkor jogainak érvényesítése érdekében a Nemzeti Adatvédelmi és Információszabadság Hatósághoz (elérhetőségek lentebb) fordulhat, és",
            options: [
                {
                    name: "vizsgálatot kezdeményezhet",
                    description:
                        "ha véleménye szerint az Adatkezelő a 5. pontban meghatározott Érintett jogainak érvényesítését korlátozza, vagy ezen jogainak érvényesítésére irányuló kérelmét elutasítja, vagy",
                },
                {
                    name: "hatósági eljárás lefolytatását kérelmezheti",
                    description:
                        ", ha megítélése szerint az Adatkezelő személyes adatainak kezelése során megsérti az adatkezelésre vonatkozó főbb jogszabályokban meghatározott előírásokat.",
                },
            ],
            authority: {
                headers: {
                    name: "Hivatalos név:",
                    address: "Székhely:",
                    postalAddress: "Postacím:",
                    phoneNumber: "Telefonszám:",
                    faxNumber: "Faxszám:",
                    email: "Központi elektronikus levélcím:",
                    website: "Honlap:",
                },
                values: {
                    name: "Nemzeti Adatvédelmi és Információszabadság Hatóság (NAIH) ",
                    address: "1055 Budapest, Falk Miksa utca 9-11. ",
                    postalAddress: "1363 Budapest, Pf.: 9.",
                    phoneNumber: "+36 (1) 391-1400",
                    faxNumber: "+36 (1) 391-1410 6.",
                    email: "ugyfelszolgalat@naih.hu",
                    website: "https://www.naih.hu",
                },
            },
        },
    },
    timescope: {
        title: "Az Adatkelezési Tájékoztató hatálya",
        description:
            "Jelen tájékoztatót az Adatkezelő az adattörléskor, de legkésőbb a következő tanév első tanítási napját megelőző 15 nap előtt felülvizsgálja, és ha kell, módosítja. Az tájékoztató módosítása esetén a régi változatot le kell cserélni a módosított változatra a módosítást követő 15 napon belül. A tájékoztató közzététele mellett az Adatkezelő feltünteti a hatályos változat érvényességének kezdeti időpontját.",
    },
};

const PrivacyPolicyPage = () => {
    return (
        <div className="mx-auto max-w-4xl">
            <h1 className="text-center text-xl">{locale.title}</h1>
            <div className="text-center">{locale.date}</div>
            <section className="my-4">
                <h2 className=" text-lg font-bold">1. {locale.laws.title}</h2>
                <ol className="list-lower-latin ml-16">
                    {locale.laws.array.map((e) => (
                        <li key={e}>{e}</li>
                    ))}
                </ol>
            </section>
            <section className="my-4">
                <h2 className="text-center text-lg font-bold">
                    2. {locale.participants.title}
                </h2>
                <div>
                    <h3 className="font-bold">
                        2.1 {locale.participants.handler.title}
                    </h3>
                    <table className="mb-2 w-full text-left">
                        <tbody>
                            <tr>
                                <th scope="row">
                                    {locale.participants.handler.headers.name}
                                </th>
                                <td>
                                    {locale.participants.handler.values.name}
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    {
                                        locale.participants.handler.headers
                                            .address
                                    }
                                </th>
                                <td>
                                    {locale.participants.handler.values.address}
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    {locale.participants.handler.headers.phone}
                                </th>
                                <td>
                                    {locale.participants.handler.values.phone}
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    {locale.participants.handler.headers.email}
                                </th>
                                <td>
                                    <Link
                                        to={`mailto:${locale.participants.handler.values.email}`}
                                    >
                                        {
                                            locale.participants.handler.values
                                                .email
                                        }
                                    </Link>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    {
                                        locale.participants.handler.headers
                                            .website
                                    }
                                </th>
                                <td>
                                    <Link
                                        to={
                                            locale.participants.handler.values
                                                .website
                                        }
                                    >
                                        {
                                            locale.participants.handler.values
                                                .website
                                        }{" "}
                                    </Link>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    {
                                        locale.participants.handler.headers
                                            .activity_location
                                    }
                                </th>
                                <td>
                                    {
                                        locale.participants.handler.values
                                            .activity_location
                                    }
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    {
                                        locale.participants.handler.headers
                                            .storageprovider
                                    }
                                </th>
                                <td>
                                    {" "}
                                    {
                                        locale.participants.handler.values
                                            .storageprovider
                                    }
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <p>{locale.participants.handler.description}</p>
                </div>
                <div className="my-2">
                    <h2 className="font-bold">
                        2.2 {locale.participants.processor.title}
                    </h2>
                    <p>{locale.participants.processor.description}</p>
                </div>
                <div className="my-2">
                    <h2 className="font-bold">
                        2.3 {locale.participants.owner.title}
                    </h2>
                    <p>{locale.participants.owner.description}</p>
                </div>
            </section>
            <section>
                <h2 className="text-center text-lg font-bold">
                    3. {locale.handling.title}
                </h2>
                <div className="my-2">
                    <h3 className="font-bold">
                        3.1 {locale.handling.principles.title}
                    </h3>
                    <div>
                        {locale.handling.principles.items.map((e) => (
                            <p key={e.title}>
                                <span className="mr-2 font-bold">
                                    {e.title}:{" "}
                                </span>
                                {e.description}
                            </p>
                        ))}
                    </div>
                    <p className="mt-3 font-bold">
                        {locale.handling.principles.accountability}
                    </p>
                </div>
                <div className="my-2">
                    <h3 className=" font-bold">
                        3.2 {locale.handling.basis.title}
                    </h3>
                    <p>{locale.handling.basis.description}</p>
                </div>
                <div className="my-2">
                    <h3 className=" font-bold">
                        3.3 {locale.handling.goal.title}
                    </h3>
                    <p>{locale.handling.goal.description}</p>
                </div>
                <div className="my-2">
                    <h3 className="font-bold">
                        3.4 {locale.handling.tech.title}
                    </h3>
                    <div>
                        <p>{locale.handling.tech.description}</p>
                        <div className="mt-2">
                            <span>{locale.handling.tech.sourcesIntro}</span>
                            <ol className="list-lower-latin ml-16">
                                {locale.handling.tech.sources.map((e) => (
                                    <li key={e}>{e}</li>
                                ))}
                            </ol>
                        </div>
                        <p className="mt-2">{locale.handling.tech.location}</p>
                    </div>
                </div>
                <div className="my-2">
                    <h3 className="font-bold">
                        3.5 {locale.handling.access.title}
                    </h3>
                    <div>
                        <span>{locale.handling.access.intro}</span>
                        <ol className="list-lower-latin ml-16">
                            {locale.handling.access.accessors.map((e) => (
                                <li key={e}>{e}</li>
                            ))}
                        </ol>
                        <div>{locale.handling.access.location}</div>
                    </div>
                </div>
                <div className="my-2">
                    <h3 className="font-bold">
                        3.6 {locale.handling.duration.title}
                    </h3>
                    <p>{locale.handling.duration.description}</p>
                </div>
            </section>
            <section>
                <h2 className="text-center text-lg font-bold">
                    4. {locale.miscellaneous.title}
                </h2>
                <div className="my-2">
                    <h3 className="font-bold">
                        4.1 {locale.miscellaneous.securityMeasures.title}
                    </h3>
                    <div className="my-2">
                        <span>
                            {locale.miscellaneous.securityMeasures.intro}
                        </span>
                        <ol className="list-lower-latin ml-16">
                            {locale.miscellaneous.securityMeasures.sources.map(
                                (e) => (
                                    <li key={e}>{e}</li>
                                ),
                            )}
                        </ol>
                    </div>
                    <div className="my-2">
                        <span>
                            {
                                locale.miscellaneous.securityMeasures
                                    .techMeasuresIntro
                            }
                        </span>
                        <ol className="list-lower-latin ml-16">
                            {locale.miscellaneous.securityMeasures.techMeasures.map(
                                (e) => (
                                    <li key={e}>{e}</li>
                                ),
                            )}
                        </ol>
                    </div>
                    <div className="my-2">
                        <span>
                            {
                                locale.miscellaneous.securityMeasures
                                    .circumstancesIntro
                            }
                        </span>
                        <ol className="list-lower-latin ml-16">
                            {locale.miscellaneous.securityMeasures.circumstances.map(
                                (e) => (
                                    <li key={e}>{e}</li>
                                ),
                            )}
                        </ol>
                    </div>
                    <p className="my-2">
                        {locale.miscellaneous.securityMeasures.methods}
                    </p>
                    <p className="my-2">
                        {locale.miscellaneous.securityMeasures.policy}
                    </p>
                </div>
                <div className="my-2">
                    <h3 className="font-bold">
                        4.1 {locale.miscellaneous.cookie.title}
                    </h3>
                    <p>{locale.miscellaneous.cookie.description}</p>
                    <h4>{locale.miscellaneous.cookie.cookiesIntro}</h4>
                    <table className="mb-2 w-full text-left">
                        <thead>
                            <tr>
                                <th>
                                    {
                                        locale.miscellaneous.cookie
                                            .cookiesHeader.name
                                    }
                                </th>
                                <th>
                                    {
                                        locale.miscellaneous.cookie
                                            .cookiesHeader.duration
                                    }
                                </th>
                                <th>
                                    {
                                        locale.miscellaneous.cookie
                                            .cookiesHeader.description
                                    }
                                </th>
                                <th>
                                    {
                                        locale.miscellaneous.cookie
                                            .cookiesHeader.required
                                    }
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            {locale.miscellaneous.cookie.cookies.map((e) => (
                                <tr key={e.name}>
                                    <td>{e.name}</td>
                                    <td>{e.duration}</td>
                                    <td>{e.description}</td>
                                    <td>{e.required}</td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </section>
            <section>
                <h2 className="text-center text-lg font-bold">
                    5. {locale.ownersRights.title}
                </h2>
                <div>
                    <span>{locale.ownersRights.rightsListIntro}</span>
                    <ol className="list-lower-latin ml-16">
                        {locale.ownersRights.rightsList.map((e) => (
                            <li key={e}>{e}</li>
                        ))}
                    </ol>
                </div>
                <div className="my-2">
                    <h3 className="text-center font-bold">
                        5.2 {locale.ownersRights.access.title}
                    </h3>
                    <p>{locale.ownersRights.access.description}</p>
                </div>
                <div className="my-2">
                    <h3 className="text-center font-bold">
                        5.3 {locale.ownersRights.correction.title}
                    </h3>
                    <p>{locale.ownersRights.correction.description}</p>
                </div>
                <div className="my-2">
                    <h3 className="text-center font-bold">
                        5.4 {locale.ownersRights.delete.title}
                    </h3>
                    <div>
                        <div className="my-2">
                            <span>{locale.ownersRights.delete.intro}</span>
                            <ol className="list-lower-latin ml-16">
                                {locale.ownersRights.delete.causes.map((e) => (
                                    <li key={e}>{e}</li>
                                ))}
                            </ol>
                        </div>
                        {locale.ownersRights.delete.description.map((e) => (
                            <p key={e}>{e}</p>
                        ))}
                    </div>
                </div>
                <div className="my-2">
                    <h3 className="text-center font-bold">
                        5.5 {locale.ownersRights.limit.title}
                    </h3>
                    <div className="my-2">
                        <span>{locale.ownersRights.limit.requestIntro}</span>
                        <ol className="list-lower-latin ml-16">
                            {locale.ownersRights.limit.requestTypes.map((e) => (
                                <li key={e}>{e}</li>
                            ))}
                        </ol>
                    </div>
                    <div className="my-2">
                        <span>{locale.ownersRights.limit.durationIntro}</span>
                        <ol className="list-lower-latin ml-16">
                            {locale.ownersRights.limit.durationScopes.map(
                                (e) => (
                                    <li key={e}>{e}</li>
                                ),
                            )}
                        </ol>
                    </div>
                    <p>{locale.ownersRights.limit.rights}</p>
                </div>
                <div className="my-2">
                    <h3 className="text-center font-bold">
                        5.6 {locale.ownersRights.portability.title}
                    </h3>
                    <p>{locale.ownersRights.portability.description}</p>
                </div>
                <div className="my-2">
                    <h3 className="text-center font-bold">
                        5.7 {locale.ownersRights.complain.title}
                    </h3>
                    <p>{locale.ownersRights.complain.description}</p>
                </div>
            </section>
            <section>
                <h2 className="text-center text-lg font-bold">
                    6. {locale.complaint.title}
                </h2>
                <div>
                    <h3 className="text-center font-bold">
                        6.1 {locale.complaint.contact.title}
                    </h3>
                    <div className="my-2">
                        <span>{locale.complaint.contact.intro}</span>
                        <ol className="list-lower-latin ml-16">
                            {locale.complaint.contact.causes.map((e) => (
                                <li key={e}>{e}</li>
                            ))}
                        </ol>
                    </div>
                    <p>{locale.complaint.contact.deadline}</p>
                    <div className="my-2">
                        <span>{locale.complaint.contact.cost}</span>
                        <ol className="list-lower-latin ml-16">
                            {locale.complaint.contact.exceptWhen.map((e) => (
                                <li key={e}>{e}</li>
                            ))}
                        </ol>
                    </div>
                    <p className="font-bold">
                        {locale.complaint.contact.pleaseno}
                    </p>
                    <div>
                        <span>{locale.complaint.contact.damagesIntro}</span>
                        <ol className="list-lower-latin ml-16">
                            {locale.complaint.contact.damagesExceptions.map(
                                (e) => (
                                    <li key={e}>{e}</li>
                                ),
                            )}
                        </ol>
                    </div>
                </div>
                <div className="my-2">
                    <h3 className="text-center font-bold">
                        6.2 {locale.complaint.courtStart.title}
                    </h3>
                    <p>{locale.complaint.courtStart.description}</p>
                </div>
                <div>
                    <h3 className="text-center font-bold">
                        6.3 {locale.complaint.authoritiesStart.title}
                    </h3>
                    <div className="my-2">
                        <span>{locale.complaint.authoritiesStart.intro}</span>
                        <ol className="list-lower-latin ml-16">
                            {locale.complaint.authoritiesStart.options.map(
                                (e) => (
                                    <li key={e.name}>
                                        <span className="font-bold">
                                            {e.name}
                                        </span>
                                        {e.description}
                                    </li>
                                ),
                            )}
                        </ol>
                    </div>
                    <table className="mb-2 w-full text-left">
                        <tbody>
                            <tr>
                                <th scope="row">
                                    {
                                        locale.complaint.authoritiesStart
                                            .authority.headers.name
                                    }
                                </th>
                                <td>
                                    {
                                        locale.complaint.authoritiesStart
                                            .authority.values.name
                                    }
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    {
                                        locale.complaint.authoritiesStart
                                            .authority.headers.address
                                    }
                                </th>
                                <td>
                                    {
                                        locale.complaint.authoritiesStart
                                            .authority.values.address
                                    }
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    {
                                        locale.complaint.authoritiesStart
                                            .authority.headers.postalAddress
                                    }
                                </th>
                                <td>
                                    {
                                        locale.complaint.authoritiesStart
                                            .authority.values.postalAddress
                                    }
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    {
                                        locale.complaint.authoritiesStart
                                            .authority.headers.phoneNumber
                                    }
                                </th>
                                <td>
                                    {
                                        locale.complaint.authoritiesStart
                                            .authority.values.phoneNumber
                                    }
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    {
                                        locale.complaint.authoritiesStart
                                            .authority.headers.faxNumber
                                    }
                                </th>
                                <td>
                                    {
                                        locale.complaint.authoritiesStart
                                            .authority.values.faxNumber
                                    }
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    {
                                        locale.complaint.authoritiesStart
                                            .authority.headers.email
                                    }
                                </th>
                                <td>
                                    <Link
                                        to={`mailto:${locale.complaint.authoritiesStart.authority.values.email}`}
                                    >
                                        {
                                            locale.complaint.authoritiesStart
                                                .authority.values.email
                                        }
                                    </Link>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    {
                                        locale.complaint.authoritiesStart
                                            .authority.headers.website
                                    }
                                </th>
                                <td>
                                    <Link
                                        to={
                                            locale.complaint.authoritiesStart
                                                .authority.values.website
                                        }
                                    >
                                        {
                                            locale.complaint.authoritiesStart
                                                .authority.values.website
                                        }
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
            <section>
                <h2 className="text-center text-lg font-bold">
                    7. {locale.timescope.title}
                </h2>
                <p className="my-2">{locale.timescope.description}</p>
            </section>
        </div>
    );
};

export default PrivacyPolicyPage;
