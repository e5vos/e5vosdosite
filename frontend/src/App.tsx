import { useState } from "react";
import reactLogo from "./assets/react.svg";
import {atomWithStorage} from 'jotai/utils'
import "./App.scss";
import { useAtom } from "jotai";

const counterAtom = atomWithStorage('counter', 0)



function App() {
  const [count, setCount] = useAtom(counterAtom);
  return (
    <div className="App">
      <div>
        <a href="https://vitejs.dev" rel="noopener" target="_blank">
          <img src="/vite.svg" className="logo" alt="Vite logo" />
        </a>
        <a href="https://reactjs.org"  rel="noopener" target="_blank">
          <img src={reactLogo} className="logo react" alt="React logo" />
        </a>
      </div>
      <h1>Vite + React</h1>
      <div className="card">
        <button onClick={() => setCount((count) => count + 1)}>
          count is {count}
        </button>
        <p>
          Edit <code>src/App.tsx</code> and save to test HMR
        </p>
      </div>
      <p className="read-the-docs">
        Click on the Vite and React logos to learn more
      </p>
    </div>
  );
}

export default App;
