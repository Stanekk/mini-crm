<?php

namespace App\Controller;

use App\Dto\CreateCompanyRequestDto;
use App\Entity\Company;
use App\Mapper\CompanyMapper;
use App\Repository\CompanyRepository;
use App\Service\CompanyService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CompanyController extends AbstractController
{
    private CompanyService $companyService;
    private CompanyMapper $companyMapper;
    private CompanyRepository $companyRepository;
    private ValidatorInterface $validator;

    public function __construct(CompanyService $companyService, CompanyMapper $companyMapper, CompanyRepository $companyRepository, ValidatorInterface $validator)
    {
        $this->companyService = $companyService;
        $this->companyMapper = $companyMapper;
        $this->companyRepository = $companyRepository;
        $this->validator = $validator;
    }

    #[Route('/api/companies', name: 'app_company_list', methods: ['GET'])]
    public function index(): JSONResponse
    {
        $companies = $this->companyRepository->findAll();
        $companiesDto = array_map(fn(Company $company) => $this->companyMapper->toDto($company), $companies);
        
        return new JsonResponse($companiesDto, Response::HTTP_OK);
    }

    #[Route('/api/companies', name: 'app_company_create', methods: ['POST'])]
    public function create(#[MapRequestPayload(validationGroups: ['Default'])] CreateCompanyRequestDto $dto): JSONResponse
    {
        $company = $this->companyService->create($dto);
        $responseDto = $this->companyMapper->toDto($company);

        return new JsonResponse($responseDto, Response::HTTP_CREATED);
    }

    #[Route('/api/companies/{id}', name: 'app_company_details', methods: ['GET'])]
    public function details(int $id): JSONResponse
    {
        $company = $this->companyService->getCompanyById($id);
        if (!$company) {
            throw new NotFoundHttpException('Company not found.');
        }

        $companyDto = $this->companyMapper->toDto($company);

        return new JsonResponse($companyDto, Response::HTTP_OK);
    }

    #[Route('/api/companies/{id}', name: 'app_company_delete', methods: ['DELETE'])]
    public function delete(int $id): JSONResponse
    {
        $company = $this->companyService->getCompanyById($id);
        if (!$company) {
            throw new NotFoundHttpException('Company not found.');
        }

        $this->companyService->deleteCompany($company);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/companies/{id}', name: 'app_company_edit', methods: ['PATCH'])]
    public function update(int $id, Request $request): JSONResponse
    {
        $company = $this->companyService->getCompanyById($id);
        $data = $request->toArray();
        if (!$company) {
            throw new NotFoundHttpException('Company not found.');
        }

        $company = $this->companyService->updateCompany($company, $data);

        $companyDto = $this->companyMapper->toDto($company);

        return new JsonResponse($companyDto, Response::HTTP_OK);
    }
}