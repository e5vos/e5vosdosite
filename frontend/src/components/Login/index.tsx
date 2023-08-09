import { useCallback, useEffect } from "react";
import { useNavigate, useParams } from "react-router-dom";
import { authSlice } from "reducers/authReducer";

import Locale from "lib/locale";
import routeSwitcher from "lib/route";
import { useDispatch, useSelector } from "lib/store";

import Button from "components/UIKit/Button";

const locale = Locale({
  hu: {
    login_with_e5account: "Bejelentkezés E5vös fiókkal",
  },
  en: {
    login_with_e5account: "Login with E5 account",
  },
});

/**
 * @param  {Object} options
 * @return {Window}
 */
const openWindow = (
  url: string,
  title: string,
  options: { [key: string]: any } = {}
) => {
  if (typeof url === "object") {
    options = url;
    url = "";
  }
  options = { url, title, width: 600, height: 720, ...options };
  const dualScreenLeft =
    /** @ts-ignore */

    window.screenLeft !== undefined ? window.screenLeft : window.screen.left;
  const dualScreenTop =
    /** @ts-ignore */
    window.swcreenTop !== undefined ? window.screenTop : window.screen.top;
  const width =
    window.innerWidth ||
    document.documentElement.clientWidth ||
    window.screen.width;
  const height =
    window.innerHeight ||
    document.documentElement.clientHeight ||
    window.screen.height;
  options.left = width / 2 - options.width / 2 + dualScreenLeft;
  options.top = height / 2 - options.height / 2 + dualScreenTop;
  const optionsStr = Object.keys(options)
    .reduce((acc, key) => {
      /** @ts-ignore */
      acc.push(`${key}=${options[key]}`);
      return acc;
    }, [])
    .join(",");
  const newWindow = window.open(url, title, optionsStr);
  newWindow?.focus();
  return newWindow;
};

const logIn = async () => {
  const newWindow = openWindow("", "Google Login");
  const url = await fetch(routeSwitcher("login"))
    .then((res) => res.json())
    .then((res) => res.url);
  if (newWindow) newWindow.location.href = url;
};

const LoginForm = () => {
  const navigate = useNavigate();
  const params = useParams();
  const dispatch = useDispatch();
  const token = useSelector((state) => state.auth.token);
  const onMessage = useCallback<(this: Window, e: MessageEvent<any>) => any>(
    (e) => {
      if (!e.data.token) {
        return;
      }
      // e.data.token contains token
      dispatch(authSlice.actions.setToken(e.data.token));
    },
    [dispatch]
  );

  useEffect(() => {
    if (token) console.log("tc redir");
    if (token) navigate(params.next ?? "/eloadas");
  }, [navigate, params.next, token]);

  useEffect(() => {
    window.addEventListener("message", onMessage);
    return () => {
      window.removeEventListener("message", onMessage);
    };
  }, [onMessage]);

  return (
    <div className="m-1 mx-auto text-center">
      <Button onClick={logIn}>{locale.login_with_e5account}</Button>
    </div>
  );
};

export default LoginForm;
