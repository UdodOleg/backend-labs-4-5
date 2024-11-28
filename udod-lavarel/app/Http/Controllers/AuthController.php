<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class AuthController extends Controller
{
    /**
     * Get the authenticated user's details
     */
    public function user(Request $request)
    {
        try {
            $user = Auth::user();
            $token = Auth::token();
            
            return response()->json([
                'user' => $user,
                'token' => $token,
                'decoded_token' => Auth::decodedToken()
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }

    /**
     * Test endpoint to verify token
     */
    public function test(Request $request)
    {
        try {
            return response()->json([
                'token' => Auth::token(),
                'message' => 'Token is valid',
                'user' => Auth::user()
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }

    /**
     * Get user roles from token
     */
    public function roles(Request $request)
    {
        try {
            $token = Auth::decodedToken();
            $roles = $token['resource_access'] ?? [];
            
            return response()->json([
                'roles' => $roles,
                'realm_roles' => $token['realm_access']['roles'] ?? []
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }

    /**
     * Check if user has specific role
     */
    public function hasRole(Request $request, string $role)
    {
        try {
            $token = Auth::decodedToken();
            $realmRoles = $token['realm_access']['roles'] ?? [];
            $hasRole = in_array($role, $realmRoles);
            
            return response()->json([
                'has_role' => $hasRole,
                'role' => $role
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }

    /**
     * Validate token and return its contents
     */
    public function validateToken(Request $request)
    {
        try {
            $token = Auth::token();
            $decodedToken = Auth::decodedToken();
            
            return response()->json([
                'valid' => true,
                'token' => $token,
                'decoded' => $decodedToken,
                'expires_in' => $decodedToken['exp'] - time()
            ]);
        } catch (Exception $e) {
            return response()->json([
                'valid' => false,
                'error' => $e->getMessage()
            ], 401);
        }
    }

    /**
     * Get user permissions from token
     */
    public function permissions(Request $request)
    {
        try {
            $token = Auth::decodedToken();
            $permissions = [];
            
            // Extract permissions from resource_access
            if (isset($token['resource_access'])) {
                foreach ($token['resource_access'] as $resource => $access) {
                    if (isset($access['roles'])) {
                        $permissions[$resource] = $access['roles'];
                    }
                }
            }
            
            return response()->json([
                'permissions' => $permissions
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }

    /**
     * Get token metadata
     */
    public function tokenInfo(Request $request)
    {
        try {
            $token = Auth::decodedToken();
            
            return response()->json([
                'issued_at' => date('Y-m-d H:i:s', $token['iat']),
                'expires_at' => date('Y-m-d H:i:s', $token['exp']),
                'issuer' => $token['iss'] ?? null,
                'audience' => $token['aud'] ?? null,
                'subject' => $token['sub'] ?? null,
                'type' => $token['typ'] ?? null,
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }
}