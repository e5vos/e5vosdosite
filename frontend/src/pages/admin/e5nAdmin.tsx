import { gated } from "components/Gate";
import Button from "components/UIKit/Button";
import { isOperator } from "lib/gates";

const AdminPage = () => {
  return (
    <div className="mx-auto max-w-4xl">
      <h1 className="text-center font-bold text-4xl">
        Eötvös Napok Adminisztrátor
      </h1>

      <div className="flex gap-64 mt-4">
        <div className="">
          <h3 className="mb-3 text-center font-bold text-2xl">Rendszer</h3>
          <Button>Cache clear</Button>
        </div>
        <div className="">
          <h3 className="mb-3 text-center font-bold text-2xl">Rendszer</h3>
          <Button></Button>
        </div>
      </div>
    </div>
  );
};

export default gated(AdminPage, isOperator);
