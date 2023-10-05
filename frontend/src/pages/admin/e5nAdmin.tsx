import adminAPI from "lib/api/adminAPI";
import { isOperator } from "lib/gates";
import Locale from "lib/locale";

import { gated } from "components/Gate";
import Button from "components/UIKit/Button";

const locale = Locale({
  hu: {
    title: "Eötvös Napok Adminisztrátor",
    system: "Rendszer",
    cacheClear: "Cache törlése",
  },
  en: {
    title: "Eötvös Napok Admin",
    system: "System",
    cacheClear: "Clear cache",
  }
});

const AdminPage = () => {
  const [clearCache] = adminAPI.useClearCacheMutation();

  return (
    <div className="mx-auto max-w-4xl">
      <h1 className="text-center text-4xl font-bold">{locale.title}</h1>

      <div className="mt-4 flex gap-64">
        <div className="">
          <h3 className="mb-3 text-center text-2xl font-bold">{locale.system}</h3>
          <Button onClick={() => clearCache()} variant="danger">
            {locale.cacheClear}
          </Button>
        </div>
        <div className="">
          <h3 className="mb-3 text-center text-2xl font-bold">{locale.system}</h3>
          <Button></Button>
        </div>
      </div>
    </div>
  );
};

export default gated(AdminPage, isOperator);
