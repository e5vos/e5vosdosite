import { api } from "lib/api";
import { isOperator } from "lib/gates";

import { gated } from "components/Gate";
import Button from "components/UIKit/Button";

const AdminPage = () => {
  const [clearCache] = api.useClearCacheMutation();

  return (
    <div className="mx-auto max-w-4xl">
      <h1 className="text-center text-4xl font-bold">
        Eötvös Napok Adminisztrátor
      </h1>

      <div className="mt-4 flex gap-64">
        <div className="">
          <h3 className="mb-3 text-center text-2xl font-bold">Rendszer</h3>
          <Button onClick={() => clearCache()} variant="danger">
            Cache clear
          </Button>
        </div>
        <div className="">
          <h3 className="mb-3 text-center text-2xl font-bold">Rendszer</h3>
          <Button></Button>
        </div>
      </div>
    </div>
  );
};

export default gated(AdminPage, isOperator);
