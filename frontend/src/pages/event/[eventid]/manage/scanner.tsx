import Error, { HTTPErrorCode } from "components/Error";
import ErrorMsgBox from "components/UIKit/ErrorMsgBox";
import Loader from "components/UIKit/Loader";
import useUser from "hooks/useUser";
import { api } from "lib/api";
import { useState } from "react";
import { QrReader } from "react-qr-reader";
import { useParams } from "react-router-dom";

const Scanner = () => {
  const { eventid } = useParams();
  const { data: event, error } = api.useGetEventQuery(Number(eventid));
  const [attend] = api.useSignUpMutation();
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
        <h1 className=" text-4xl font-bold">{event.name} - QR Olvasó</h1>
        <h3>
          {user.name} - {user?.ejg_class}
        </h3>
      </div>
      <QrReader
        onResult={(result, error) => {
          if (!!result) {
            handleCode(result?.getText());
          }
        }}
        constraints={{ facingMode: "environment" }}
      />
    </div>
  );
};
export default Scanner;
