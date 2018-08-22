<?php
    namespace App\Http\Middleware;
    use Closure;
    use JWTAuth;
    use Tymon\JWTAuth\Exceptions\JWTException;
    use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
    use App\Traits\ApiResponse;
    class VerifyJWTToken
    {
        /**
         * Handle an incoming request.
         *
         * @param  \Illuminate\Http\Request  $request
         * @param  \Closure  $next
         * @return mixed
         */
         
        use ApiResponse;
        
        public function handle($request, Closure $next)
        {
              try {
                if (! $user = JWTAuth::parseToken()->authenticate()) {
             
             return $this->respondWithError('UN_AUTHORIZED',[]);
                  
            }
            } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
               return $this->respondWithSuccessError($e->getMessage(),[]);
                   
                } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
             
              return $this->respondWithSuccessError($e->getMessage(),[]);
                   
                } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
                    return $this->respondWithSuccessError($e->getMessage(),[]);
                }
            
               return $next($request);
        }
    }