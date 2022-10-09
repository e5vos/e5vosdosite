import React, { ReactNode, useState } from "react";
import { paginatedLoader } from "lib/paginatedLoader";
import Loader from "components/UIKit/Loader";
import Paginator from "./Paginator";

interface Props<T> {
  Head: ReactNode;
  Body: React.FC<{ data: T | undefined }>;
  Loader?: React.FC<{}>;
  url: URL | undefined;
  pageSize?: number;
}

export default function PaginatedTable<T>({
  Head,
  Body,
  url,
  Loader: CustomLoader,
  pageSize = 10,
  ...rest
}: Props<T> &
  React.DetailedHTMLProps<
    React.TableHTMLAttributes<HTMLTableElement>,
    HTMLTableElement
  >) {
  const pageCountURL = url ? new URL(url) : undefined;
  if (pageCountURL) {
    pageCountURL.pathname = pageCountURL.pathname.concat("/pagecount");
    pageCountURL.searchParams.delete("cursor");
    pageCountURL.searchParams.set("limit", String(pageSize));
  }

  const [currentPage, setCurrentPage] = useState<number>(1);
  const { data, setSize }: {data: {data:any}[], setSize: (e:number) => any} = {data: [], setSize: (newSize: number) => {}};
  const { data: pageCount } = {data: 5}

  const setPage = (page: number) => {
    setSize(page);
    setCurrentPage(page);
  };
  const UsedLoader = CustomLoader ?? Loader;
  if (!pageCount) return <UsedLoader />;
  return (
    <>
      <table {...rest}>
        <>
          {Head}
          {pageCount === 0 ||
          (data && data[currentPage - 1] && data[currentPage - 1].data) ? (
            <Body
              data={
                data && data[currentPage - 1] !== undefined
                  ? data[currentPage - 1].data
                  : undefined
              }
            />
          ) : (
            <UsedLoader />
          )}
        </>
      </table>
      {pageCount > 1 ? (
        <Paginator
          currentPage={currentPage}
          pageCount={pageCount}
          setCurrentPage={setPage}
        />
      ) : (
        <></>
      )}
    </>
  );
}
