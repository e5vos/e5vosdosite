import { QrReader } from "react-qr-reader";

const Scanner = () => {
  return (
    <>
      <QrReader
        onResult={(result, error) => {
          if (!!result) {
          }
          if (!!error) {
            console.log(error);
          }
        }}
        constraints={{ facingMode: "environment" }}
      />
    </>
  );
};
export default Scanner;
