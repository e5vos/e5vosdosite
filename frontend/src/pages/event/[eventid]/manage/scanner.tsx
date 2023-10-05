import useUser from "hooks/useUser";
import { QrReader } from "react-qr-reader";
import { useParams } from "react-router-dom";

import eventAPI from "lib/api/eventAPI";

import Error, { HTTPErrorCode } from "components/Error";
import Loader from "components/UIKit/Loader";
import Locale from "lib/locale";


const locale = Locale({
  hu: {
    scanner: "QR Olvasó",
  },
  en: {
    scanner: "QR Scanner",
  }
})

const Scanner = () => {
  const { eventid } = useParams();
  const { data: event, error } = eventAPI.useGetEventQuery(Number(eventid));
  const [attend] = eventAPI.useSignUpMutation();
  const { user } = useUser();

  const handleCode = (s: string) => {
    console.log("handleCode", s);
  };
  if (error && "status" in error)
    return <Error code={error.status as HTTPErrorCode} />;
  if (!event || !user) return <Loader />;

  return (
    <div className="container mx-auto">
      <div className="text-center">
        <h1 className=" text-4xl font-bold">{event.name} - {locale.scanner}</h1>
        <h3>
          {user.name} - {user?.ejg_class}
        </h3>
      </div>
      <QrReader
        onResult={(result, error) => {
          if (result) {
            handleCode(result?.getText());
          }
        }}
        constraints={{ facingMode: "environment" }}
      />
    </div>
  );
};
export default Scanner;
