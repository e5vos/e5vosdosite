import { Link } from "react-router-dom";

const locale = {
    title: "A Budapest V. kerületi Eötvös József Gimnázium Diákönkormányzat honlapjának Adatkezelési Tájékoztatója",
    date: "2023 október",
    laws: {
        title: "Az adatkezelésre vonatkozó főbb jogszabályok",
        array: [
            "a)	A természetes személyeknek a személyes adatok kezelése tekintetében történő védelméről és az ilyen adatok szabad áramlásáról, valamint a 95/46/EK rendelet hatályon kívül helyezéséről szóló 2016. április 27-ei 2016/679 Európai Parlamenti és Tanácsi (EU) rendelet (a továbbiakban: GDPR),",
            "b)	Az információs önrendelkezési jogról és az információszabadságról szóló 2011. évi CXII. törvény (a továbbiakban: Infotv.),",
            "c)	Az elektronikus kereskedelmi szolgáltatások, valamint az információs társadalommal összefüggő szolgáltatások egyes kérdéseiről szóló 2001. évi CVIII. törvény (Ektv.), és",
            "d)	A nemzeti köznevelésről szóló 2011. évi CXC. törvény (a továbbiakban: Nkt.)",
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
        },
        basis: {
            title: "Az adatkezelés jogalapja",
        },
        goal: {
            title: "Az adatkezelés célja",
        },
    },
};

const PrivacyPolicyPage = () => {
    return (
        <div>
            <h1>{locale.title}</h1>
            <h2>{locale.date}</h2>
            <h3>{locale.laws.title}</h3>
            <ul>
                {locale.laws.array.map((e) => (
                    <li>{e}</li>
                ))}
            </ul>
            <h3>{locale.participants.title}</h3>
            <h4>{locale.participants.handler.title}</h4>
            <table>
                <tr>
                    <th scope="row">
                        {locale.participants.handler.headers.name}
                    </th>
                    <td>{locale.participants.handler.values.name}</td>
                </tr>
                <tr>
                    <th scope="row">
                        {locale.participants.handler.headers.address}
                    </th>
                    <td>{locale.participants.handler.values.address}</td>
                </tr>
                <tr>
                    <th scope="row">
                        {locale.participants.handler.headers.phone}
                    </th>
                    <td>{locale.participants.handler.values.phone}</td>
                </tr>
                <tr>
                    <th scope="row">
                        {locale.participants.handler.headers.email}
                    </th>
                    <td>
                        <Link
                            to={`mailto:${locale.participants.handler.values.email}`}
                        >
                            {locale.participants.handler.values.email}
                        </Link>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        {locale.participants.handler.headers.website}
                    </th>
                    <td>
                        <Link to={locale.participants.handler.values.website}>
                            {locale.participants.handler.values.website}{" "}
                        </Link>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        {locale.participants.handler.headers.activity_location}
                    </th>
                    <td>
                        {locale.participants.handler.values.activity_location}
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        {locale.participants.handler.headers.storageprovider}
                    </th>
                    <td>
                        {" "}
                        {locale.participants.handler.values.storageprovider}
                    </td>
                </tr>
            </table>
            <p>{locale.participants.handler.description}</p>
            <h3>{locale.participants.processor.title}</h3>
            <p>{locale.participants.processor.description}</p>
            <h3>{locale.participants.owner.title}</h3>
            <p>{locale.participants.owner.description}</p>
        </div>
    );
};

export default PrivacyPolicyPage;
